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

        $pendingAppointments = Appointment::with(['patient', 'doctor'])
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingAppointments'));
    }

    private function dokterDashboard()
    {
        $dokter = auth()->user();
        
        $stats = [
            'pending_appointments' => Appointment::where('dokter_id', $dokter->id)->pending()->count(),
            'today_appointments' => Appointment::where('dokter_id', $dokter->id)
                ->approved()
                ->whereDate('tanggal_booking', today())
                ->count(),
            'total_patients' => Appointment::where('dokter_id', $dokter->id)
                ->select('pasien_id')
                ->distinct()
                ->count(),
        ];

        $todayAppointments = Appointment::with(['patient', 'schedule'])
            ->where('dokter_id', $dokter->id)
            ->approved()
            ->whereDate('tanggal_booking', today())
            ->orderBy('tanggal_booking')
            ->get();

        return view('dokter.dashboard', compact('stats', 'todayAppointments'));
    }

    private function pasienDashboard()
    {
        $pasien = auth()->user();
        
        $lastAppointment = Appointment::with(['doctor', 'doctor.poli'])
            ->where('pasien_id', $pasien->id)
            ->latest()
            ->first();

        $approvedAppointments = Appointment::with(['doctor', 'doctor.poli'])
            ->where('pasien_id', $pasien->id)
            ->approved()
            ->where('tanggal_booking', '>=', today())
            ->get();

        return view('pasien.dashboard', compact('lastAppointment', 'approvedAppointments'));
    }
}