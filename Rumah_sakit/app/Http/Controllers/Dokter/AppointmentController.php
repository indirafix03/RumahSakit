<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $appointments = Appointment::with(['pasien', 'dokter.poli', 'schedule'])
            ->where('dokter_id', $user->id)
            ->orderBy('tanggal_booking', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by status
        $pendingAppointments = $appointments->where('status', 'pending');
        $approvedAppointments = $appointments->where('status', 'approved');
        $rejectedAppointments = $appointments->where('status', 'rejected');
        $completedAppointments = $appointments->where('status', 'selesai');

        return view('dokter.appointments.index', compact(
            'pendingAppointments',
            'approvedAppointments', 
            'rejectedAppointments',
            'completedAppointments'
        ));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        \Log::info('=== UPDATE STATUS DEBUG ===');
        \Log::info('Appointment ID: ' . $appointment->id);
        \Log::info('Status: ' . $request->status);
        \Log::info('Alasan dari Request: ' . $request->alasan_reject); // ← UBAH DI SINI

        if ($appointment->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // PERBAIKI VALIDASI: ganti 'alasan_penolakan' menjadi 'alasan_reject'
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'alasan_reject' => 'required_if:status,rejected|string|max:500', // ← PERBAIKAN
        ]);

        try {
            $updateData = [
                'status' => $request->status,
            ];

            // PERBAIKAN: Sekarang konsisten menggunakan 'alasan_reject'
            if ($request->status === 'rejected') {
                $updateData['alasan_reject'] = $request->alasan_reject; // ← PERBAIKAN
                \Log::info('Alasan akan disimpan: ' . $request->alasan_reject);
            } else {
                $updateData['alasan_reject'] = null;
            }

            $appointment->update($updateData);

            // DEBUG: Verifikasi
            $updated = Appointment::find($appointment->id);
            \Log::info('Data setelah update - alasan_reject: ' . $updated->alasan_reject);

            $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
            return redirect()->route('dokter.appointments.index')
                ->with('success', "Janji temu berhasil {$statusText}.");

        } catch (\Exception $e) {
            \Log::error('Error updating appointment status: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui status janji temu.');
        }
    }
    public function show(Appointment $appointment)
    {
        if ($appointment->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // PERBAIKAN: Load relasi yang benar sesuai dengan index method
        $appointment->load(['pasien', 'dokter.poli', 'schedule', 'medicalRecord']);
        return view('dokter.appointments.show', compact('appointment'));
    }
}