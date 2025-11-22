<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Poli;
use App\Models\Medicine;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isDokter()) {
            return $this->dokterDashboard();
        } elseif ($user->isPasien()) {
            return $this->pasienDashboard();
        }
        
        return view('dashboard');
    }

    private function adminDashboard()
    {
        $stats = [
            'total_pasien' => User::pasien()->count(),
            'total_dokter' => User::dokter()->count(),
            'total_poli' => Poli::count(),
            'pending_appointments' => Appointment::pending()->count(),
            'total_obat' => Medicine::count(),
            'obat_habis' => Medicine::where('stok', 0)->count(),
        ];

        $pendingAppointments = Appointment::with(['pasien', 'dokter'])
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingAppointments'));
    }

    private function dokterDashboard()
    {
        $dokter = auth()->user();
        
        // Gunakan timezone Jakarta
        $today = now()->timezone('Asia/Jakarta')->format('Y-m-d');
        
        $stats = [
            'pending_appointments' => Appointment::where('dokter_id', $dokter->id)
                ->where('status', 'pending')
                ->count(),
            'today_appointments' => Appointment::where('dokter_id', $dokter->id)
                ->where('status', 'approved')
                ->whereDate('tanggal_booking', $today) // Gunakan $today
                ->count(),
            'total_patients' => Appointment::where('dokter_id', $dokter->id)
                ->select('pasien_id')
                ->distinct()
                ->count(),
        ];

        $todayAppointments = Appointment::with(['pasien', 'schedule'])
            ->where('dokter_id', $dokter->id)
            ->where('status', 'approved')
            ->whereDate('tanggal_booking', $today) // Gunakan $today
            ->orderBy('tanggal_booking')
            ->get();

        return view('dokter.dashboard', compact('stats', 'todayAppointments'));
    }

        private function pasienDashboard()
    {
        $pasien = auth()->user();
        
        $lastAppointment = Appointment::with(['dokter', 'dokter.poli', 'schedule']) // <- gunakan 'dokter'
            ->where('pasien_id', $pasien->id)
            ->latest()
            ->first();

        $approvedAppointments = Appointment::with(['dokter', 'dokter.poli', 'schedule']) // <- gunakan 'dokter'
            ->where('pasien_id', $pasien->id)
            ->where('status', 'approved')
            ->where('tanggal_booking', '>=', today())
            ->get();

        // Hitung statistik untuk pasien
        $stats = [
            'pending_appointments' => Appointment::where('pasien_id', $pasien->id)
                ->where('status', 'pending')
                ->count(),
            'today_appointments' => Appointment::where('pasien_id', $pasien->id)
                ->where('status', 'approved')
                ->whereDate('tanggal_booking', today())
                ->count(),
            'total_appointments' => Appointment::where('pasien_id', $pasien->id)->count(),
        ];

        return view('pasien.dashboard', compact('lastAppointment', 'approvedAppointments', 'stats'));
    }
}