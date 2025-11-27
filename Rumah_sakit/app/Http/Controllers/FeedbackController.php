<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    
    // ========== UNTUK PASIEN ==========
    public function create($appointment_id)
    {
        $appointment = Appointment::with('dokter')->findOrFail($appointment_id);
        
        // Validasi 1: Hanya pemilik appointment yang bisa beri feedback
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi 2: Hanya appointment dengan status SELESAI yang bisa di feedback
        if ($appointment->status !== 'selesai') {
            return redirect()->route('pasien.dashboard')
                           ->with('error', 'Hanya bisa memberikan feedback untuk janji temu yang sudah selesai.');
        }

        // Validasi 3: Cek apakah sudah ada feedback
        if ($appointment->feedback) {
            return redirect()->route('pasien.dashboard')
                           ->with('error', 'Anda sudah memberikan feedback untuk janji temu ini.');
        }

        return view('pasien.feedback.create', compact('appointment'));
    }

    public function store(Request $request, $appointment_id)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'ulasan' => 'required|string|min:10|max:1000',
        ]);

        $appointment = Appointment::findOrFail($appointment_id);

        // Validasi authorization dan status
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi: Hanya status SELESAI yang bisa di feedback
        if ($appointment->status !== 'selesai') {
            return redirect()->route('pasien.dashboard')
                           ->with('error', 'Hanya bisa memberikan feedback untuk janji temu yang sudah selesai.');
        }

        // Cek apakah sudah ada feedback
        if ($appointment->feedback) {
            return redirect()->route('pasien.dashboard')
                           ->with('error', 'Anda sudah memberikan feedback untuk janji temu ini.');
        }

        // Create feedback
        Feedback::create([
            'appointment_id' => $appointment->id,
            'pasien_id' => Auth::id(),
            'dokter_id' => $appointment->dokter_id,
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
        ]);

        return redirect()->route('pasien.dashboard')
                         ->with('success', 'Terima kasih atas feedback Anda!');
    }

    public function index()
    {
        $feedbacks = Feedback::where('pasien_id', Auth::id()) // UBAH: query sesuai struktur
                            ->with(['dokter', 'appointment', 'dokter.poli'])
                            ->latest()
                            ->get();

        return view('pasien.feedback.index', compact('feedbacks'));
    }

    // ========== UNTUK DOKTER ==========
    public function indexDokter()
    {
        $feedbacks = Feedback::where('dokter_id', Auth::id())
                            ->with(['appointment', 'pasien'])
                            ->latest()
                            ->get();

        $averageRating = Feedback::where('dokter_id', Auth::id())->avg('rating');

        return view('dokter.feedback.index', compact('feedbacks', 'averageRating'));
    }

    // ========== UNTUK ADMIN ==========
    public function indexAdmin()
    {
        $feedbacks = Feedback::with(['dokter', 'pasien', 'appointment'])
                            ->latest()
                            ->get();

        return view('admin.feedback.index', compact('feedbacks'));
    }

    // ========== UNTUK SEMUA ROLE ==========
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        
        // Authorization check berdasarkan role
        if (Auth::user()->hasRole('admin')) {
            // Admin bisa hapus semua feedback
        } elseif (Auth::user()->hasRole('dokter')) {
            // Dokter hanya bisa hapus feedback untuk dirinya sendiri
            if ($feedback->dokter_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            // Pasien tidak boleh hapus feedback
            abort(403, 'Unauthorized action.');
        }

        $feedback->delete();

        return redirect()->back()->with('success', 'Feedback berhasil dihapus.');
    }
}