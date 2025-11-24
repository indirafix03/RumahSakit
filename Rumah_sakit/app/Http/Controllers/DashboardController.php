<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Poli;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isPasien()) {
            return redirect()->route('pasien.dashboard');
        } elseif ($user->isDokter()) {
            return redirect()->route('dokter.dashboard');
        }
        
        return view('dashboard');
    }

    private function adminDashboard()
{
    // Total pengguna berdasarkan peran
    $userStats = [
        'total_admin' => User::where('role', 'admin')->count(),
        'total_dokter' => User::where('role', 'dokter')->count(),
        'total_pasien' => User::where('role', 'pasien')->count(),
        'total_users' => User::count(),
    ];

    // Statistik appointments
    $appointmentStats = [
        'pending_appointments' => Appointment::pending()->count(),
        'approved_appointments' => Appointment::where('status', 'approved')->count(),
        'completed_appointments' => Appointment::where('status', 'selesai')->count(),
        'total_appointments' => Appointment::count(),
        'today_appointments' => Appointment::whereDate('tanggal_booking', today())->count(),
    ];

    // Statistik medicines
    $medicineStats = [
        'total_obat' => Medicine::count(),
        'obat_tersedia' => Medicine::where('stok', '>', 0)->count(),
        'obat_habis' => Medicine::where('stok', '<=', 0)->count(),
        'obat_kadaluarsa' => Medicine::where('expired_date', '<=', today())->count(),
    ];

    // Dokter yang memiliki appointment approved hari ini
    $dokterBertugasHariIni = User::where('role', 'dokter')
        ->with(['poli'])
        ->get()
        ->map(function($dokter) {
            // Hitung appointment hari ini untuk setiap dokter
            $dokter->total_appointments_today = Appointment::where('dokter_id', $dokter->id)
                ->where('status', 'approved')
                ->whereDate('tanggal_booking', today())
                ->count();
            
            $dokter->appointments_today = Appointment::where('dokter_id', $dokter->id)
                ->where('status', 'approved')
                ->whereDate('tanggal_booking', today())
                ->with(['pasien', 'schedule'])
                ->get();
                
            return $dokter;
        })
        ->sortByDesc('total_appointments_today');

    // Janji temu pending untuk approval
    $pendingAppointments = Appointment::with(['pasien', 'dokter', 'dokter.poli'])
        ->pending()
        ->latest()
        ->take(5)
        ->get();

    // PERBAIKAN: Statistik per poli dengan pengecekan null
        $poliStats = Poli::withCount(['doctors'])->get();
    
    // Hitung appointments hari ini untuk setiap poli
    $poliStats = $poliStats->map(function($poli) {
        try {
            $poli->appointments_today_count = $poli->appointments()
                ->whereDate('appointments.tanggal_booking', today())
                ->count();
        } catch (\Exception $e) {
            // Jika ada error, set ke 0 dan log error
            $poli->appointments_today_count = 0;
            \Log::warning('Error counting appointments for poli ' . $poli->id . ': ' . $e->getMessage());
        }
        return $poli;
    });

    // DEBUG: Remove this after fixing
    \Log::info('Dashboard Poli Data:', [
        'total_poli' => $poliStats->count(),
        'poli_details' => $poliStats->map(function($poli) {
            return [
                'id' => $poli->id,
                'nama_poli' => $poli->nama_poli,
                'doctors_count' => $poli->doctors_count,
                'appointments_today_count' => $poli->appointments_today_count
            ];
        })->toArray()
    ]);

    return view('admin.dashboard', compact(
        'userStats',
        'appointmentStats', 
        'medicineStats',
        'dokterBertugasHariIni',
        'pendingAppointments',
        'poliStats'
    ));
}
}