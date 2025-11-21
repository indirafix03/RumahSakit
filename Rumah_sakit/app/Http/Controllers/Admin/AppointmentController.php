<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['pasien', 'dokter', 'dokter.poli', 'schedule']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan poli
        if ($request->has('poli_id') && $request->poli_id != '') {
            $query->whereHas('dokter', function ($q) use ($request) {
                $q->where('poli_id', $request->poli_id);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal_booking', $request->tanggal);
        }

        $appointments = $query->orderBy('created_at', 'desc')->paginate(20);

        $polis = Poli::all();
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Disetujui', 
            'rejected' => 'Ditolak',
            'selesai' => 'Selesai'
        ];

        return view('admin.appointments.index', compact('appointments', 'polis', 'statuses'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['pasien', 'dokter', 'dokter.poli', 'schedule', 'medicalRecord']);
        
        return view('admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'alasan_reject' => 'required_if:status,rejected|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $appointment->update([
                'status' => $request->status,
                'alasan_reject' => $request->status === 'rejected' ? $request->alasan_reject : null,
            ]);

            DB::commit();

            $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
            return redirect()->route('admin.appointments.index')
                ->with('success', "Janji temu berhasil {$statusText}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Appointment $appointment)
    {
        try {
            // Cek apakah sudah ada rekam medis
            if ($appointment->medicalRecord) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus janji temu yang sudah memiliki rekam medis.');
            }

            $appointment->delete();

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Janji temu berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function statistics()
    {
        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'approved' => Appointment::where('status', 'approved')->count(),
            'rejected' => Appointment::where('status', 'rejected')->count(),
            'completed' => Appointment::where('status', 'selesai')->count(),
            'today' => Appointment::whereDate('tanggal_booking', today())->count(),
        ];

        // Statistik per poli
        $poliStats = DB::table('appointments')
            ->join('users', 'appointments.dokter_id', '=', 'users.id')
            ->join('polis', 'users.poli_id', '=', 'polis.id')
            ->select('polis.nama_poli', DB::raw('COUNT(*) as total'))
            ->groupBy('polis.nama_poli')
            ->get();

        return response()->json([
            'stats' => $stats,
            'poliStats' => $poliStats
        ]);
    }
}