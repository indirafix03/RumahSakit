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
            // 1. Hitung janji temu pending
            $pendingCount = \App\Models\Appointment::where('dokter_id', $user->id)
                ->where('status', 'pending')
                ->count();

            // 2. Ambil janji temu approved untuk hari ini
            $todayApprovedAppointments = \App\Models\Appointment::with(['pasien', 'schedule'])
                ->where('dokter_id', $user->id)
                ->where('status', 'approved')
                ->whereDate('tanggal_booking', $today)
                ->orderBy('schedule_id')
                ->get();

            // 3. Ambil 5 pasien terbaru dari medical records
            $recentPatients = \App\Models\MedicalRecord::with('pasien')
                ->where('dokter_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // 4. PERBAIKAN: Ambil jadwal praktik hari ini dengan format yang benar
            $hariIni = $this->getHariIndonesiaLowerCase($today); // Gunakan format lowercase
            $schedulesToday = \App\Models\Schedule::where('dokter_id', $user->id)
                ->where('hari', $hariIni)
                ->orderBy('jam_mulai')
                ->get();

            // Debug info (bisa dihapus setelah fix)
            \Log::info('Dashboard Data', [
                'dokter_id' => $user->id,
                'hari_sekarang' => $hariIni,
                'jadwal_ditemukan' => $schedulesToday->count(),
                'janji_hari_ini' => $todayApprovedAppointments->count()
            ]);

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

    /**
     * PERBAIKAN: Return hari dalam format lowercase sesuai database
     */
    private function getHariIndonesiaLowerCase($date)
    {
        $days = [
            'Sunday' => 'minggu',
            'Monday' => 'senin',
            'Tuesday' => 'selasa', 
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu'
        ];
        
        $englishDay = Carbon::parse($date)->format('l');
        return $days[$englishDay] ?? strtolower($englishDay);
    }

    /**
     * Method tambahan untuk debug
     */
    public function debugSchedules()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $hariIni = $this->getHariIndonesiaLowerCase($today);
        
        $allSchedules = \App\Models\Schedule::where('dokter_id', $user->id)->get();
        $todaySchedules = \App\Models\Schedule::where('dokter_id', $user->id)
            ->where('hari', $hariIni)
            ->get();

        return response()->json([
            'dokter_id' => $user->id,
            'dokter_name' => $user->name,
            'hari_sekarang' => $hariIni,
            'tanggal' => $today,
            'semua_jadwal' => $allSchedules,
            'jadwal_hari_ini' => $todaySchedules,
            'total_jadwal' => $allSchedules->count(),
            'jadwal_hari_ini_count' => $todaySchedules->count()
        ]);
    }
}