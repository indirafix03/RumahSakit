<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\User;
use App\Models\Poli;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasienController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Janji Temu Pending (Status 'pending')
        $pendingAppointments = Appointment::where('pasien_id', $userId)
                                          ->where('status', 'pending')
                                          ->count();

        // 2. Janji Temu Hari Ini (Status 'approved' dan tanggal hari ini)
        $todayAppointments = Appointment::where('pasien_id', $userId)
                                        ->where('status', 'approved')
                                        ->whereDate('tanggal_booking', Carbon::today())
                                        ->count();
        
        // 3. Janji Temu Terakhir (Data detail janji temu terbaru)
        $latestAppointment = Appointment::with(['dokter.poli', 'schedule'])
                                        ->where('pasien_id', $userId)
                                        ->latest('created_at')
                                        ->first();

        // 4. Resep Siap / Total Rekam Medis (Placeholder: Menghitung semua rekam medis pasien)
        // Jika Anda memiliki model Resep terpisah dengan status "siap ambil", Anda bisa menggantinya di sini.
        $readyPrescriptions = MedicalRecord::where('pasien_id', $userId)->count();


        return view('pasien.dashboard', compact(
            'pendingAppointments',
            'todayAppointments',
            'latestAppointment',
            'readyPrescriptions'
        ));
    }

    /**
     * Display patient dashboard
     */
    public function dashboard(){
        $patient = Auth::user();
        
        // Get latest appointment - sesuaikan dengan relationship di model
        $latestAppointment = Appointment::where('pasien_id', $patient->id)
            ->with(['dokter', 'schedule']) // <- gunakan 'dokter' sesuai model
            ->latest()
            ->first();
            
        // Get pending appointments count
        $pendingAppointments = Appointment::where('pasien_id', $patient->id)
            ->where('status', 'pending')
            ->count();
            
        // Get approved appointments for today
        $todayAppointments = Appointment::where('pasien_id', $patient->id)
            ->where('status', 'approved')
            ->whereDate('tanggal_booking', today())
            ->count();
            
        // Get prescriptions ready for pickup
        $readyPrescriptions = 0;
        if (class_exists(Prescription::class)) {
            $readyPrescriptions = Prescription::where('pasien_id', $patient->id)
                ->where('status', 'ready')
                ->count();
        }

        return view('pasien.dashboard', compact(
            'latestAppointment',
            'pendingAppointments',
            'todayAppointments',
            'readyPrescriptions'
        ));
    }

    /**
     * Display patient appointments
     */
    public function appointments()
    {
        $patient = Auth::user();
        
        $appointments = Appointment::where('pasien_id', $patient->id)
            ->with(['dokter', 'schedule']) // <- gunakan 'dokter' sesuai model
            ->orderBy('tanggal_booking', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pasien.appointments.index', compact('appointments'));
    }
    /**
     * Show the form for creating a new appointment
     */
    public function createAppointment()
    {
        $polis = Poli::with(['doctors' => function($query) {
            $query->where('role', 'dokter');
        }])->get();

        return view('pasien.appointments.create', compact('polis'));
    }

    /**
     * Store a newly created appointment
     */
    /**
 * Store a newly created appointment
 */
    public function storeAppointment(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:schedules,id',
            'tanggal_booking' => 'required|date|after_or_equal:today', // Bisa hari ini
            'keluhan_singkat' => 'required|string|max:500'
        ]);

        // Cek apakah appointment sudah ada di tanggal dan schedule yang sama
        $existingAppointment = Appointment::where('pasien_id', Auth::id())
            ->where('schedule_id', $request->schedule_id)
            ->where('tanggal_booking', $request->tanggal_booking)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingAppointment) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda sudah memiliki janji temu pada tanggal dan waktu yang sama.');
        }

        // Cek kuota appointment untuk schedule tersebut
        $appointmentCount = Appointment::where('schedule_id', $request->schedule_id)
            ->where('tanggal_booking', $request->tanggal_booking)
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($appointmentCount >= 5) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Slot waktu ini sudah penuh. Silakan pilih waktu lain.');
        }

        $appointment = Appointment::create([
            'pasien_id' => Auth::id(),
            'dokter_id' => $request->dokter_id,
            'schedule_id' => $request->schedule_id,
            'tanggal_booking' => $request->tanggal_booking,
            'keluhan_singkat' => $request->keluhan_singkat,
            'status' => 'pending'
        ]);

        return redirect()->route('pasien.appointments.index')
            ->with('success', 'Janji temu berhasil dibuat dan menunggu validasi.');
    }

    /**
     * Display the specified appointment
     */
    public function showAppointment($id)
    {
        $appointment = Appointment::where('pasien_id', Auth::id())
            ->with(['dokter', 'schedule'])
            ->findOrFail($id);

        return view('pasien.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function editAppointment($id)
{
    $appointment = Appointment::where('pasien_id', Auth::id())
        ->where('status', 'pending')
        ->with(['dokter', 'schedule'])
        ->findOrFail($id);

    $polis = Poli::with(['doctors' => function($query) {
        $query->where('role', 'dokter');
    }])->get();

    // Get available schedules for the current doctor and date
    $availableSchedules = [];
    if ($appointment->dokter_id && $appointment->tanggal_booking) {
        $availableSchedules = $this->getAvailableSchedulesForEdit(
            $appointment->dokter_id, 
            $appointment->tanggal_booking->format('Y-m-d'),
            $appointment->id // exclude current appointment
        );
    }

    return view('pasien.appointments.edit', compact('appointment', 'polis', 'availableSchedules'));
}

/**
 * Helper method to get available schedules for edit
 */
/**
 * Helper method to get available schedules for edit
 */
private function getAvailableSchedulesForEdit($dokterId, $date, $excludeAppointmentId = null)
{
    \Log::info('=== getAvailableSchedulesForEdit START ===', [
        'dokterId' => $dokterId, 
        'date' => $date,
        'excludeAppointmentId' => $excludeAppointmentId
    ]);

    $dayMap = [
        'Sunday' => 'minggu',
        'Monday' => 'senin', 
        'Tuesday' => 'selasa',
        'Wednesday' => 'rabu',
        'Thursday' => 'kamis',
        'Friday' => 'jumat',
        'Saturday' => 'sabtu'
    ];

    try {
        $carbonDate = \Carbon\Carbon::parse($date);
        $englishDay = $carbonDate->englishDayOfWeek;
        $dayName = $dayMap[$englishDay] ?? null;

        \Log::info('Date parsed for edit', [
            'date' => $date,
            'english_day' => $englishDay,
            'indonesian_day' => $dayName
        ]);

        if (!$dayName) {
            \Log::warning('Day name not found for edit date', ['date' => $date]);
            return [];
        }

        $schedules = Schedule::where('dokter_id', $dokterId)
            ->where('hari', $dayName)
            ->get();

        \Log::info('Schedules found for edit', [
            'count' => $schedules->count(),
            'schedules' => $schedules->pluck('jam_mulai', 'id')
        ]);

        // Filter schedules yang belum penuh (exclude current appointment)
        $availableSlots = $schedules->filter(function($schedule) use ($date, $dokterId, $excludeAppointmentId) {
            $query = Appointment::where('dokter_id', $dokterId)
                ->where('schedule_id', $schedule->id)
                ->where('tanggal_booking', $date)
                ->whereIn('status', ['pending', 'approved']);

            if ($excludeAppointmentId) {
                $query->where('id', '!=', $excludeAppointmentId);
            }

            $appointmentCount = $query->count();

            $isAvailable = $appointmentCount < 5;
            
            \Log::info('Edit schedule availability', [
                'schedule_id' => $schedule->id,
                'jam_mulai' => $schedule->jam_mulai,
                'appointment_count' => $appointmentCount,
                'is_available' => $isAvailable
            ]);

            return $isAvailable;
        })->map(function($schedule) {
            // Calculate jam_selesai from jam_mulai + durasi
            $jamMulai = \Carbon\Carbon::parse($schedule->jam_mulai);
            $jamSelesai = $jamMulai->copy()->addMinutes($schedule->durasi);
            
            return [
                'id' => $schedule->id,
                'jam_mulai' => $schedule->jam_mulai,
                'jam_selesai' => $jamSelesai->format('H:i:s'),
                'durasi' => $schedule->durasi
            ];
        });

        \Log::info('=== getAvailableSchedulesForEdit RESULT ===', [
            'available_count' => $availableSlots->count()
        ]);

        return $availableSlots->values();

    } catch (\Exception $e) {
        \Log::error('Error in getAvailableSchedulesForEdit', [
            'error' => $e->getMessage(),
            'dokterId' => $dokterId,
            'date' => $date
        ]);
        
        return [];
    }
}
    /**
     * Update the specified appointment
     */
    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::where('pasien_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $request->validate([
            'dokter_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:schedules,id',
            'tanggal_booking' => 'required|date|after:today',
            'keluhan_singkat' => 'required|string|max:500'
        ]);

        // Cek apakah appointment sudah ada di tanggal dan schedule yang sama (kecuali appointment ini)
        $existingAppointment = Appointment::where('pasien_id', Auth::id())
            ->where('schedule_id', $request->schedule_id)
            ->where('tanggal_booking', $request->tanggal_booking)
            ->whereIn('status', ['pending', 'approved'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingAppointment) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda sudah memiliki janji temu pada tanggal dan waktu yang sama.');
        }

        // Cek kuota appointment untuk schedule tersebut
        $appointmentCount = Appointment::where('schedule_id', $request->schedule_id)
            ->where('tanggal_booking', $request->tanggal_booking)
            ->whereIn('status', ['pending', 'approved'])
            ->where('id', '!=', $id)
            ->count();

        if ($appointmentCount >= 5) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Slot waktu ini sudah penuh. Silakan pilih waktu lain.');
        }

        $appointment->update([
            'dokter_id' => $request->dokter_id,
            'schedule_id' => $request->schedule_id,
            'tanggal_booking' => $request->tanggal_booking,
            'keluhan_singkat' => $request->keluhan_singkat
        ]);

        return redirect()->route('pasien.appointments.index')
            ->with('success', 'Janji temu berhasil diperbarui.');
    }

    /**
     * Remove the specified appointment
     */
    public function destroyAppointment($id)
    {
        $appointment = Appointment::where('pasien_id', Auth::id())
            ->whereIn('status', ['pending', 'rejected'])
            ->findOrFail($id);

        $appointment->delete();

        return redirect()->route('pasien.appointments.index')
            ->with('success', 'Janji temu berhasil dihapus.');
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment($id)
    {
        $appointment = Appointment::where('pasien_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $appointment->update([
            'status' => 'rejected',
            'alasan_reject' => 'Dibatalkan oleh pasien'
        ]);

        return redirect()->route('pasien.appointments.index')
            ->with('success', 'Janji temu berhasil dibatalkan.');
    }

    /**
     * Display patient medical records
     */
    public function medicalRecords()
    {
        $patient = Auth::user();
        
        $medicalRecords = MedicalRecord::where('pasien_id', $patient->id)
            ->with(['dokter', 'appointment', 'prescriptions.medicine'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pasien.medical-records.index', compact('medicalRecords'));
    }

    /**
     * Get doctors by poli
     */
   /**
 * Get doctors by poli
 */
public function getDoctorsByPoli($poliId)
{
    \Log::info('=== getDoctorsByPoli START ===', ['poliId' => $poliId]);
    
    try {
        // Query doctors berdasarkan poli_id
        $doctors = User::where('poli_id', $poliId)
            ->where('role', 'dokter')
            ->with(['schedules']) // Load schedules tanpa filter status
            ->get()
            ->map(function($doctor) {
                \Log::info('Doctor data', [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'schedules_count' => $doctor->schedules->count()
                ]);
                
                // Calculate jam_selesai from jam_mulai + durasi
                $formattedSchedules = $doctor->schedules->map(function($schedule) {
                    $jamMulai = \Carbon\Carbon::parse($schedule->jam_mulai);
                    $jamSelesai = $jamMulai->copy()->addMinutes($schedule->durasi);
                    
                    return [
                        'id' => $schedule->id,
                        'hari' => $schedule->hari,
                        'jam_mulai' => $schedule->jam_mulai,
                        'jam_selesai' => $jamSelesai->format('H:i:s'),
                        'durasi' => $schedule->durasi
                    ];
                });
                
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'spesialisasi' => $doctor->spesialisasi,
                    'schedules' => $formattedSchedules
                ];
            });

        \Log::info('=== getDoctorsByPoli RESULT ===', [
            'doctors_count' => $doctors->count(),
            'doctors' => $doctors->pluck('name')
        ]);

        return response()->json($doctors);

    } catch (\Exception $e) {
        \Log::error('Error in getDoctorsByPoli', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'poliId' => $poliId
        ]);
        
        return response()->json([]);
    }
}
    /**
 * Get available time slots for doctor
 */
public function getDoctorTimeSlots($dokterId, $date)
{
    \Log::info('=== getDoctorTimeSlots START ===', ['dokterId' => $dokterId, 'date' => $date]);
    
    // Validasi input
    if (!$dokterId || !$date) {
        \Log::warning('Invalid parameters', ['dokterId' => $dokterId, 'date' => $date]);
        return response()->json([]);
    }

    $dayMap = [
        'Sunday' => 'minggu',
        'Monday' => 'senin', 
        'Tuesday' => 'selasa',
        'Wednesday' => 'rabu',
        'Thursday' => 'kamis',
        'Friday' => 'jumat',
        'Saturday' => 'sabtu'
    ];

    try {
        // Parse date dengan validasi
        $carbonDate = \Carbon\Carbon::parse($date);
        $englishDay = $carbonDate->englishDayOfWeek;
        $dayName = $dayMap[$englishDay] ?? null;

        \Log::info('Date parsed', [
            'input_date' => $date,
            'carbon_date' => $carbonDate->toDateString(),
            'english_day' => $englishDay,
            'indonesian_day' => $dayName
        ]);

        if (!$dayName) {
            \Log::warning('Day name not found for date', [
                'date' => $date,
                'english_day' => $englishDay
            ]);
            return response()->json([]);
        }

        // Cek apakah dokter exists dan memiliki role dokter
        $dokter = User::where('id', $dokterId)
            ->where('role', 'dokter')
            ->first();
            
        if (!$dokter) {
            \Log::warning('Dokter not found or not a doctor', ['dokterId' => $dokterId]);
            return response()->json([]);
        }

        \Log::info('Dokter found', ['dokter_name' => $dokter->name]);

        // Get schedules for the specific day
        $schedules = Schedule::where('dokter_id', $dokterId)
            ->where('hari', $dayName)
            ->get();

        \Log::info('Schedules query result', [
            'dokter_id' => $dokterId,
            'hari' => $dayName,
            'schedules_count' => $schedules->count(),
            'schedules' => $schedules->toArray()
        ]);

        if ($schedules->count() === 0) {
            \Log::info('No schedules found for this day', [
                'dokter' => $dokter->name,
                'day' => $dayName
            ]);
            return response()->json([]);
        }

        // Filter schedules yang belum penuh
        $availableSlots = $schedules->filter(function($schedule) use ($date, $dokterId) {
            $appointmentCount = Appointment::where('dokter_id', $dokterId)
                ->where('schedule_id', $schedule->id)
                ->where('tanggal_booking', $date)
                ->whereIn('status', ['pending', 'approved'])
                ->count();

            $isAvailable = $appointmentCount < 5; // Maksimal 5 appointment per slot
            
            \Log::info('Schedule availability check', [
                'schedule_id' => $schedule->id,
                'jam_mulai' => $schedule->jam_mulai,
                'appointment_count' => $appointmentCount,
                'max_capacity' => 5,
                'is_available' => $isAvailable
            ]);

            return $isAvailable;
        })->map(function($schedule) {
            // Calculate jam_selesai from jam_mulai + durasi
            $jamMulai = \Carbon\Carbon::parse($schedule->jam_mulai);
            $jamSelesai = $jamMulai->copy()->addMinutes($schedule->durasi);
            
            return [
                'id' => $schedule->id,
                'jam_mulai' => $schedule->jam_mulai,
                'jam_selesai' => $jamSelesai->format('H:i:s'),
                'durasi' => $schedule->durasi,
                'hari' => $schedule->hari
            ];
        });

        \Log::info('=== getDoctorTimeSlots RESULT ===', [
            'available_slots_count' => $availableSlots->count(),
            'available_slots' => $availableSlots->toArray()
        ]);

        return response()->json($availableSlots->values());

    } catch (\Exception $e) {
        \Log::error('Error in getDoctorTimeSlots', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'dokterId' => $dokterId,
            'date' => $date
        ]);
        
        return response()->json([]);
    }
}
}