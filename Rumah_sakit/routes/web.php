<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Dokter Controllers (used in the Dokter group)
use App\Http\Controllers\Dokter\ScheduleController;
use App\Http\Controllers\Dokter\AppointmentController;
use App\Http\Controllers\Dokter\MedicalRecordController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

// Guest Routes
Route::get('/poli', function () {
    return view('guest.poli');
})->name('poli.public');

Route::get('/dokter', function () {
    return view('guest.dokter');
})->name('dokter.public');

// Auth Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users (Consolidated to resource for brevity, but original explicit routes are also fine)
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);

    // Poli
    Route::resource('polis', App\Http\Controllers\Admin\PoliController::class);
    
    // Obat/Medicines
    Route::resource('medicines', App\Http\Controllers\Admin\MedicineController::class);
    
    // Appointments (CORRECTION: Used dedicated Admin controller FQN)
    $adminAppointmentController = App\Http\Controllers\Admin\AppointmentController::class;

    Route::get('/appointments', [$adminAppointmentController, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [$adminAppointmentController, 'show'])->name('appointments.show');
    Route::put('/appointments/{appointment}/status', [$adminAppointmentController, 'updateStatus'])->name('appointments.updateStatus');
    Route::delete('/appointments/{appointment}', [$adminAppointmentController, 'destroy'])->name('appointments.destroy');
    Route::get('/appointments-statistics', [$adminAppointmentController, 'statistics'])->name('appointments.statistics');
});

// Dokter Routes
Route::middleware(['auth', 'verified', 'dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Schedules (Consolidated to resource for brevity)
    Route::resource('schedules', ScheduleController::class)->except(['show', 'create', 'edit']); 
    
    // Appointments (Using the imported Dokter\AppointmentController, which is correct here)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::put('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
    
    // Medical Records (Using the imported Dokter\MedicalRecordController)
    Route::resource('medical-records', MedicalRecordController::class)->except(['show']);

    Route::resource('medical-records', MedicalRecordController::class)->only(['index', 'create', 'store', 'show']);

});

// Pasien Routes
Route::prefix('pasien')->middleware(['auth', 'pasien'])->name('pasien.')->group(function () {
    $pasienController = App\Http\Controllers\Pasien\PasienController::class;

    // Appointments - CRUD
    Route::get('/appointments', [$pasienController, 'appointments'])->name('appointments.index');
    Route::get('/appointments/create', [$pasienController, 'createAppointment'])->name('appointments.create');
    Route::post('/appointments', [$pasienController, 'storeAppointment'])->name('appointments.store');
    Route::get('/appointments/{id}', [$pasienController, 'showAppointment'])->name('appointments.show');
    Route::get('/appointments/{id}/edit', [$pasienController, 'editAppointment'])->name('appointments.edit');
    Route::put('/appointments/{id}', [$pasienController, 'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/{id}', [$pasienController, 'destroyAppointment'])->name('appointments.destroy');
    Route::post('/appointments/{id}/cancel', [$pasienController, 'cancelAppointment'])->name('appointments.cancel');

    // Medical Records (CORRECTION: Added missing 'show' route)
    Route::get('/medical-records', [$pasienController, 'medicalRecords'])->name('medical-records.index');
    Route::get('/medical-records/{id}', [$pasienController, 'showMedicalRecord'])->name('medical-records.show'); // ⬅️ Rute tambahan untuk melihat detail
    
    // AJAX
    Route::get('/get-doctors/{poliId}', [$pasienController, 'getDoctorsByPoli'])->name('get-doctors');
    Route::get('/get-time-slots/{dokterId}/{date}', [$pasienController, 'getDoctorTimeSlots'])->name('get-time-slots');
});

// routes/web.php - tambahkan route debugging
Route::get('/debug-appointments', function() {
    $dokter = auth()->user();
    
    if (!$dokter || !$dokter->isDokter()) {
        return "Hanya untuk dokter";
    }
    
    echo "<h2>Janji Temu Hari Ini untuk Dr. {$dokter->name}</h2>";
    
    $appointments = \App\Models\Appointment::with(['pasien', 'schedule'])
        ->where('dokter_id', $dokter->id)
        ->where('status', 'approved')
        ->whereDate('tanggal_booking', today())
        ->get();
    
    if ($appointments->count() === 0) {
        echo "<p class='text-warning'>Tidak ada janji temu yang disetujui untuk hari ini.</p>";
        
        // Tampilkan semua janji temu untuk debugging
        $allAppointments = \App\Models\Appointment::with(['pasien', 'schedule'])
            ->where('dokter_id', $dokter->id)
            ->get();
            
        echo "<h3>Semua Janji Temu:</h3>";
        if ($allAppointments->count() === 0) {
            echo "<p>Tidak ada janji temu sama sekali</p>";
        } else {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Pasien</th><th>Tanggal</th><th>Status</th><th>Dokter ID</th></tr>";
            foreach ($allAppointments as $appt) {
                echo "<tr>";
                echo "<td>{$appt->pasien->name}</td>";
                echo "<td>{$appt->tanggal_booking->format('d/m/Y')}</td>";
                echo "<td>{$appt->status}</td>";
                echo "<td>{$appt->dokter_id}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Pasien</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Keluhan</th></tr>";
        foreach ($appointments as $appt) {
            echo "<tr>";
            echo "<td>{$appt->id}</td>";
            echo "<td>{$appt->pasien->name}</td>";
            echo "<td>{$appt->tanggal_booking->format('d/m/Y')}</td>";
            echo "<td>" . ($appt->schedule ? $appt->schedule->jam_mulai : 'N/A') . "</td>";
            echo "<td>{$appt->status}</td>";
            echo "<td>{$appt->keluhan_singkat}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    die();
});


require __DIR__.'/auth.php';