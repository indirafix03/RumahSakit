<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        try {
            // 1. Hitung janji temu pending - PERBAIKAN DI SINI
            $pendingCount = \App\Models\Appointment::where('dokter_id', $user->id)
                ->where('status', 'pending')
                ->count();

            // 2. Ambil janji temu approved untuk hari ini
            $todayApprovedAppointments = \App\Models\Appointment::with(['pasien', 'schedule'])
                ->where('dokter_id', $user->id)
                ->where('status', 'approved')
                ->whereDate('tanggal_booking', $today)
                ->get();

            // 3. Ambil 5 pasien terbaru dari medical records
            $recentPatients = \App\Models\MedicalRecord::with('pasien')
                ->where('dokter_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->unique('pasien_id')
                ->values();

            // 4. Ambil jadwal praktik hari ini
            $hariIni = $this->getHariIndonesia($today);
            $schedulesToday = \App\Models\Schedule::where('dokter_id', $user->id)
                ->where('hari', $hariIni)
                ->orderBy('jam_mulai')
                ->get();

            return view('dokter.dashboard', compact(
                'pendingCount',
                'todayApprovedAppointments',
                'recentPatients',
                'schedulesToday',
                'today'
            ));

        } catch (\Exception $e) {
            // Fallback jika ada error
            \Log::error('Dashboard error: ' . $e->getMessage());
            
            return view('dokter.dashboard', [
                'pendingCount' => 0,
                'todayApprovedAppointments' => collect(),
                'recentPatients' => collect(),
                'schedulesToday' => collect(),
                'today' => $today
            ]);
        }
    }

    private function getHariIndonesia($date)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        
        $englishDay = Carbon::parse($date)->format('l');
        return $days[$englishDay] ?? $englishDay;
    }
}