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
use Carbon\Carbon;

class PasienController extends Controller
{
    /**
     * Display patient dashboard - GUNAKAN SATU METHOD SAJA
     */
    public function index()
    {
        $patient = Auth::user(); // Gunakan $patient, bukan $userId
        
        // 1. Janji Temu Pending (Status 'pending')
        $pendingAppointments = Appointment::where('pasien_id', $patient->id)
                                          ->where('status', 'pending')
                                          ->count();

        // 2. Janji Temu Hari Ini (Status 'approved' dan tanggal hari ini)
        $todayAppointments = Appointment::where('pasien_id', $patient->id)
                                        ->where('status', 'approved')
                                        ->whereDate('tanggal_booking', today()) // Gunakan today() helper
                                        ->count();
        
        // 3. Janji Temu Terakhir (Data detail janji temu terbaru)
        $latestAppointment = Appointment::with(['dokter.poli', 'schedule'])
                                        ->where('pasien_id', $patient->id)
                                        ->latest('created_at')
                                        ->first();

        // 4. Resep Siap Diambil
        $readyPrescriptions = Prescription::forPatient($patient->id)
                                          ->ready()
                                          ->count();

        // 5. Resep Dalam Proses
        $pendingPrescriptions = Prescription::forPatient($patient->id)
                                            ->pending()
                                            ->count();

        // 6. Total Rekam Medis
        $totalMedicalRecords = MedicalRecord::where('pasien_id', $patient->id)->count();

        return view('pasien.dashboard', compact(
            'pendingAppointments',
            'todayAppointments',
            'latestAppointment',
            'readyPrescriptions',
            'pendingPrescriptions',
            'totalMedicalRecords'
        ));
    }

    // HAPUS method dashboard() yang duplicate
    // public function dashboard() { ... }

    /**
     * Display patient appointments
     */
    public function appointments()
    {
        $patient = Auth::user();
        
        $appointments = Appointment::where('pasien_id', $patient->id)
            ->with(['dokter', 'schedule'])
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
    public function storeAppointment(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:schedules,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
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
                $appointment->id
            );
        }

        return view('pasien.appointments.edit', compact('appointment', 'polis', 'availableSchedules'));
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
     * Show individual medical record
     */
    public function showMedicalRecord($id)
    {
        $medicalRecord = MedicalRecord::where('pasien_id', Auth::id())
            ->with(['dokter', 'appointment', 'prescriptions.medicine'])
            ->findOrFail($id);

        return view('pasien.medical-records.show', compact('medicalRecord'));
    }

    public function information()
    {
        $polis = Poli::withCount('doctors')->get();
        
        return view('pasien.information', compact('polis'));
    }

    /**
     * Display patient prescriptions
     */
    public function prescriptions()
    {
        $patient = Auth::user();
        
        $readyPrescriptions = Prescription::forPatient($patient->id)
            ->with([
                'medicalRecord.dokter', 
                'medicalRecord.appointment',
                'medicine'
            ])
            ->ready()
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingPrescriptions = Prescription::forPatient($patient->id)
            ->with([
                'medicalRecord.dokter',
                'medicalRecord.appointment', 
                'medicine'
            ])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();

        $takenPrescriptions = Prescription::forPatient($patient->id)
            ->with([
                'medicalRecord.dokter',
                'medicalRecord.appointment',
                'medicine'
            ])
            ->taken()
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('pasien.prescriptions.index', compact(
            'readyPrescriptions',
            'pendingPrescriptions',
            'takenPrescriptions'
        ));
    }

    /**
     * Confirm prescription pickup (mark as taken)
     */
    public function confirmPickup($id)
    {
        try {
            $prescription = Prescription::forPatient(Auth::id())
                ->ready()
                ->findOrFail($id);

            $prescription->markAsTaken();

            return response()->json([
                'success' => true,
                'message' => 'Resep berhasil dikonfirmasi sebagai sudah diambil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resep tidak ditemukan atau tidak dapat dikonfirmasi'
            ], 404);
        }
    }

    /**
     * Show prescription detail
     */
    public function showPrescription($id)
    {
        $prescription = Prescription::forPatient(Auth::id())
            ->with([
                'medicalRecord.dokter',
                'medicalRecord.appointment',
                'medicine'
            ])
            ->findOrFail($id);

        return view('pasien.prescriptions.show', compact('prescription'));
    }

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
                ->with(['schedules' => function($query) {
                    $query->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
                        ->orderBy('jam_mulai');
                }])
                ->get()
                ->map(function($doctor) {
                    \Log::info('Doctor data', [
                        'id' => $doctor->id,
                        'name' => $doctor->name,
                        'schedules_count' => $doctor->schedules->count()
                    ]);
                    
                    // Format schedules dengan detail lengkap
                    $formattedSchedules = $doctor->schedules->map(function($schedule) {
                        $jamMulai = \Carbon\Carbon::parse($schedule->jam_mulai);
                        $jamSelesai = $jamMulai->copy()->addMinutes($schedule->durasi);
                        
                        return [
                            'id' => $schedule->id,
                            'hari' => $schedule->hari,
                            'hari_indonesia' => $this->getIndonesianDayName($schedule->hari),
                            'jam_mulai' => $schedule->jam_mulai,
                            'jam_mulai_formatted' => $jamMulai->format('H:i'),
                            'jam_selesai' => $jamSelesai->format('H:i:s'),
                            'jam_selesai_formatted' => $jamSelesai->format('H:i'),
                            'durasi' => $schedule->durasi,
                            'slot_text' => $jamMulai->format('H:i') . ' - ' . $jamSelesai->format('H:i')
                        ];
                    });

                    // Group schedules by day untuk tampilan yang lebih terorganisir
                    $groupedSchedules = $formattedSchedules->groupBy('hari_indonesia')->map(function($schedules, $day) {
                        return [
                            'day_name' => $day,
                            'slots' => $schedules->map(function($slot) {
                                return [
                                    'id' => $slot['id'],
                                    'time' => $slot['slot_text'],
                                    'jam_mulai' => $slot['jam_mulai_formatted'],
                                    'jam_selesai' => $slot['jam_selesai_formatted'],
                                    'durasi' => $slot['durasi']
                                ];
                            })
                        ];
                    })->values();

                    return [
                        'id' => $doctor->id,
                        'name' => $doctor->name,
                        'spesialisasi' => $doctor->spesialisasi,
                        'total_schedules' => $doctor->schedules->count(),
                        'schedules' => $formattedSchedules,
                        'grouped_schedules' => $groupedSchedules,
                        'available_days' => $formattedSchedules->pluck('hari_indonesia')->unique()->values()
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

    private function getIndonesianDayName($day)
{
    $days = [
        'monday' => 'Senin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jumat',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu',
        'senin' => 'Senin',
        'selasa' => 'Selasa',
        'rabu' => 'Rabu',
        'kamis' => 'Kamis',
        'jumat' => 'Jumat',
        'sabtu' => 'Sabtu',
        'minggu' => 'Minggu'
    ];

    return $days[strtolower($day)] ?? ucfirst($day);
}

    /**
     * Get available time slots for doctor
     */
    public function getDoctorTimeSlots($dokterId, $date)
    {
        \Log::info('=== getDoctorTimeSlots START ===', ['dokterId' => $dokterId, 'date' => $date]);
        
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

            $dokter = User::where('id', $dokterId)
                ->where('role', 'dokter')
                ->first();
                
            if (!$dokter) {
                \Log::warning('Dokter not found or not a doctor', ['dokterId' => $dokterId]);
                return response()->json([]);
            }

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

            $formattedSchedules = $schedules->map(function($schedule) {
                $jamMulai = \Carbon\Carbon::parse($schedule->jam_mulai)
                    ->timezone('UTC')
                    ->setTimezone('Asia/Jakarta');
                
                $jamSelesai = \Carbon\Carbon::parse($schedule->jam_selesai)
                    ->timezone('UTC')
                    ->setTimezone('Asia/Jakarta');

                return [
                    'id' => $schedule->id,
                    'jam_mulai' => $jamMulai->format('H:i'),
                    'jam_selesai' => $jamSelesai->format('H:i')
                ];
            });

            return response()->json($formattedSchedules);

        } catch (\Exception $e) {
            \Log::error('Error in getDoctorTimeSlots', [
                'dokterId' => $dokterId,
                'date' => $date,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([]);
        }
    }

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

    public function schedules(Request $request)
    {
        $polis = Poli::withCount('doctors')->get();
        
        // Get filter parameters
        $poliFilter = $request->get('poli');
        $dayFilter = $request->get('day');
        $viewType = $request->get('view', 'grid'); // grid or list

        // Base query for schedules
        $schedulesQuery = Schedule::with(['doctor.poli'])
            ->whereHas('doctor', function($query) {
                $query->where('role', 'dokter');
            });

        // Apply poli filter
        if ($poliFilter) {
            $schedulesQuery->whereHas('doctor', function($query) use ($poliFilter) {
                $query->where('poli_id', $poliFilter);
            });
        }

        // Apply day filter
        if ($dayFilter) {
            $schedulesQuery->where('hari', $dayFilter);
        }

        $schedules = $schedulesQuery->orderBy('hari')->orderBy('jam_mulai')->get();

        // Group schedules by day for better organization
        $groupedSchedules = $schedules->groupBy('hari');

        // Get available days for filter
        $availableDays = Schedule::select('hari')
            ->distinct()
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->pluck('hari');

        return view('pasien.schedules.index', compact(
            'schedules',
            'groupedSchedules',
            'polis',
            'availableDays',
            'poliFilter',
            'dayFilter',
            'viewType'
        ));
    }

    public function getAvailableSlots($doctorId, $date)
    {
        try {
            $dayMap = [
                'Sunday' => 'minggu',
                'Monday' => 'senin', 
                'Tuesday' => 'selasa',
                'Wednesday' => 'rabu',
                'Thursday' => 'kamis',
                'Friday' => 'jumat',
                'Saturday' => 'sabtu'
            ];

            $carbonDate = Carbon::parse($date);
            $englishDay = $carbonDate->englishDayOfWeek;
            $dayName = $dayMap[$englishDay] ?? null;

            if (!$dayName) {
                return response()->json([]);
            }

            // Get doctor's schedules for the specific day
            $schedules = Schedule::where('dokter_id', $doctorId)
                ->where('hari', $dayName)
                ->get();

            // Check availability for each schedule slot
            $availableSlots = $schedules->map(function($schedule) use ($date, $doctorId) {
                $appointmentCount = Appointment::where('dokter_id', $doctorId)
                    ->where('schedule_id', $schedule->id)
                    ->where('tanggal_booking', $date)
                    ->whereIn('status', ['pending', 'approved'])
                    ->count();

                $isAvailable = $appointmentCount < 5; // Max 5 patients per slot
                $availableSeats = 5 - $appointmentCount;

                return [
                    'id' => $schedule->id,
                    'jam_mulai' => Carbon::parse($schedule->jam_mulai)->format('H:i'),
                    'jam_selesai' => Carbon::parse($schedule->jam_selesai)->format('H:i'),
                    'is_available' => $isAvailable,
                    'available_seats' => $availableSeats,
                    'appointment_count' => $appointmentCount
                ];
            });

            return response()->json($availableSlots);

        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

     public function getDoctorSchedule($doctorId)
    {
        try {
            $doctor = User::with(['poli', 'schedules'])
                ->where('id', $doctorId)
                ->where('role', 'dokter')
                ->firstOrFail();

            $schedules = $doctor->schedules->map(function($schedule) {
                return [
                    'id' => $schedule->id,
                    'hari' => $schedule->hari,
                    'jam_mulai' => Carbon::parse($schedule->jam_mulai)->format('H:i'),
                    'jam_selesai' => Carbon::parse($schedule->jam_selesai)->format('H:i'),
                    'durasi' => $schedule->durasi
                ];
            });

            return response()->json([
                'doctor' => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'spesialisasi' => $doctor->spesialisasi,
                    'poli' => $doctor->poli->nama_poli ?? 'N/A',
                    'bio' => $doctor->bio
                ],
                'schedules' => $schedules
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }
    }
}