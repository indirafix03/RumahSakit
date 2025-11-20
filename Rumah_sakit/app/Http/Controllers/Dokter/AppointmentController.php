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
        
        $appointments = Appointment::with(['pasien', 'poli'])
            ->where('dokter_id', $user->id)
            ->orderBy('tanggal_booking', 'desc')
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
        if ($appointment->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'alasan_penolakan' => 'required_if:status,rejected|string|max:500',
        ]);

        try {
            $appointment->update([
                'status' => $request->status,
                'alasan_penolakan' => $request->status === 'rejected' ? $request->alasan_penolakan : null,
            ]);

            $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
            return redirect()->route('dokter.appointments.index')
                ->with('success', "Janji temu berhasil {$statusText}.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Appointment $appointment)
    {
        if ($appointment->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->load(['pasien', 'poli', 'schedule']);
        return view('dokter.appointments.show', compact('appointment'));
    }
}