<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Dokter\ScheduleController;
use App\Http\Controllers\Dokter\AppointmentController;
use App\Http\Controllers\Dokter\MedicalRecordController;
use App\Http\Controllers\Pasien\PasienController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;

Route::get('/', function () {
    return view('welcome');
});

// Guest Routes (bisa diakses tanpa login - READ ONLY)
Route::get('/poli', [GuestController::class, 'poli'])->name('poli.public');
Route::get('/dokter', [GuestController::class, 'dokter'])->name('dokter.public');
Route::get('/dokter/{id}', [GuestController::class, 'dokterDetail'])->name('dokter.detail');

// Auth routes (dari Breeze)
require __DIR__.'/auth.php';

// Protected routes (semua route di bawah ini membutuhkan login)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard umum
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware(['verified', 'admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);

        // Poli
        Route::resource('polis', App\Http\Controllers\Admin\PoliController::class);
        
        // Obat/Medicines
        Route::resource('medicines', App\Http\Controllers\Admin\MedicineController::class);
        
        // Appointments
        $adminAppointmentController = App\Http\Controllers\Admin\AppointmentController::class;

        Route::get('/appointments', [$adminAppointmentController, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [$adminAppointmentController, 'show'])->name('appointments.show');
        Route::put('/appointments/{appointment}/status', [$adminAppointmentController, 'updateStatus'])->name('appointments.updateStatus');
        Route::delete('/appointments/{appointment}', [$adminAppointmentController, 'destroy'])->name('appointments.destroy');
        Route::get('/appointments-statistics', [$adminAppointmentController, 'statistics'])->name('appointments.statistics');

        // Medical Records untuk Admin
        $adminMedicalRecordController = App\Http\Controllers\Admin\MedicalRecordController::class;
        Route::resource('medical-records', $adminMedicalRecordController);
        Route::get('/medical-records/create/{appointment_id}', [$adminMedicalRecordController, 'create'])->name('medical-records.create');

        // FEEDBACK ADMIN
        Route::get('/feedback', [FeedbackController::class, 'indexAdmin'])->name('feedback.index');
        Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
        });

    // Dokter Routes
    Route::middleware(['auth', 'verified', 'dokter'])->group(function () {
        // DASHBOARD
        Route::get('/dokter-dashboard', [App\Http\Controllers\Dokter\DashboardController::class, 'index'])->name('dokter.dashboard');
        
        // SCHEDULES
        Route::get('/dokter-schedules', [App\Http\Controllers\Dokter\ScheduleController::class, 'index'])->name('dokter.schedules.index');
        Route::post('/dokter-schedules', [App\Http\Controllers\Dokter\ScheduleController::class, 'store'])->name('dokter.schedules.store');
        Route::get('/dokter-schedules/{schedule}/edit', [App\Http\Controllers\Dokter\ScheduleController::class, 'edit'])->name('dokter.schedules.edit');
        Route::put('/dokter-schedules/{schedule}', [App\Http\Controllers\Dokter\ScheduleController::class, 'update'])->name('dokter.schedules.update');
        Route::delete('/dokter-schedules/{schedule}', [App\Http\Controllers\Dokter\ScheduleController::class, 'destroy'])->name('dokter.schedules.destroy');

        // Alias routes for backward-compatibility (accept both dashed and slash paths)
        Route::get('/dokter/schedules', [App\Http\Controllers\Dokter\ScheduleController::class, 'index']);
        Route::get('/dokter/schedules/{schedule}', [App\Http\Controllers\Dokter\ScheduleController::class, 'edit']);
        Route::post('/dokter/schedules', [App\Http\Controllers\Dokter\ScheduleController::class, 'store']);
        Route::put('/dokter/schedules/{schedule}', [App\Http\Controllers\Dokter\ScheduleController::class, 'update']);
        Route::delete('/dokter/schedules/{schedule}', [App\Http\Controllers\Dokter\ScheduleController::class, 'destroy']);
        
        // APPOINTMENTS  
        Route::get('/dokter-appointments', [App\Http\Controllers\Dokter\AppointmentController::class, 'index'])->name('dokter.appointments.index');
        Route::get('/dokter-appointments/{appointment}', [App\Http\Controllers\Dokter\AppointmentController::class, 'show'])->name('dokter.appointments.show');
        Route::put('/dokter-appointments/{appointment}/status', [App\Http\Controllers\Dokter\AppointmentController::class, 'updateStatus'])->name('dokter.appointments.update-status');

        // Alias routes for backward-compatibility (accept both dashed and slash paths)
        Route::get('/dokter/appointments', [App\Http\Controllers\Dokter\AppointmentController::class, 'index']);
        Route::get('/dokter/appointments/{appointment}', [App\Http\Controllers\Dokter\AppointmentController::class, 'show']);
        Route::put('/dokter/appointments/{appointment}/status', [App\Http\Controllers\Dokter\AppointmentController::class, 'updateStatus']);
        
        // MEDICAL RECORDS
        Route::get('/dokter-medical-records', [App\Http\Controllers\Dokter\MedicalRecordController::class, 'index'])->name('dokter.medical-records.index');
        Route::get('/dokter-medical-records/create', [App\Http\Controllers\Dokter\MedicalRecordController::class, 'create'])->name('dokter.medical-records.create');
        Route::get('/dokter-medical-records/create/{appointment_id}', [App\Http\Controllers\Dokter\MedicalRecordController::class, 'createFromAppointment'])->name('dokter.medical-records.create-from-appointment');
        Route::post('/dokter-medical-records', [App\Http\Controllers\Dokter\MedicalRecordController::class, 'store'])->name('dokter.medical-records.store');
        Route::get('/dokter-medical-records/{medical_record}', [App\Http\Controllers\Dokter\MedicalRecordController::class, 'show'])->name('dokter.medical-records.show');
        Route::get('/dokter-medical-records/{medical_record}/edit', [App\Http\Controllers\Dokter\MedicalRecordController::class, 'edit'])->name('dokter.medical-records.edit');
        Route::put('/dokter-medical-records/{medical_record}', [App\Http\Controllers\Dokter\MedicalRecordController::class, 'update'])->name('dokter.medical-records.update');
        Route::delete('/dokter-medical-records/{medical_record}', [App\Http\Controllers\Dokter\MedicalRecordController::class, 'destroy'])->name('dokter.medical-records.destroy');

        // FEEDBACK DOKTER
        Route::get('/dokter-feedback', [FeedbackController::class, 'indexDokter'])->name('dokter.feedback.index');
        Route::delete('/dokter-feedback/{id}', [FeedbackController::class, 'destroy'])->name('dokter.feedback.destroy');
    });

    // Pasien Routes
    Route::middleware(['verified', 'pasien'])
        ->prefix('pasien')
        ->name('pasien.')
        ->group(function () {
            $pasienController = App\Http\Controllers\Pasien\PasienController::class;

        Route::get('/dashboard', [$pasienController, 'index'])->name('dashboard');
        
        // Appointments - CRUD
        Route::get('/appointments', [$pasienController, 'appointments'])->name('appointments.index');
        Route::get('/appointments/create', [$pasienController, 'createAppointment'])->name('appointments.create');
        Route::post('/appointments', [$pasienController, 'storeAppointment'])->name('appointments.store');
        Route::get('/appointments/{id}', [$pasienController, 'showAppointment'])->name('appointments.show');
        Route::get('/appointments/{id}/edit', [$pasienController, 'editAppointment'])->name('appointments.edit');
        Route::put('/appointments/{id}', [$pasienController, 'updateAppointment'])->name('appointments.update');
        Route::delete('/appointments/{id}', [$pasienController, 'destroyAppointment'])->name('appointments.destroy');
        Route::post('/appointments/{id}/cancel', [$pasienController, 'cancelAppointment'])->name('appointments.cancel');

        // Medical Records
        Route::get('/medical-records', [$pasienController, 'medicalRecords'])->name('medical-records.index');
        Route::get('/medical-records/{id}', [$pasienController, 'showMedicalRecord'])->name('medical-records.show');
        
        // AJAX
        Route::get('/get-doctors/{poliId}', [$pasienController, 'getDoctorsByPoli'])->name('get-doctors');
        Route::get('/get-time-slots/{dokterId}/{date}', [$pasienController, 'getDoctorTimeSlots'])->name('get-time-slots');

        Route::get('/information', [PasienController::class, 'information'])->name('information');

        // Prescriptions Routes
        Route::get('/prescriptions', [PasienController::class, 'prescriptions'])->name('prescriptions.index');
        Route::get('/prescriptions/{id}', [PasienController::class, 'showPrescription'])->name('prescriptions.show');
        Route::post('/prescriptions/{id}/confirm-pickup', [PasienController::class, 'confirmPickup'])->name('prescriptions.confirm-pickup');

        Route::get('/schedules', [PasienController::class, 'schedules'])->name('schedules.index');
        Route::get('/doctors/{doctorId}/schedule', [PasienController::class, 'getDoctorSchedule'])->name('doctors.schedule');
        Route::get('/available-slots/{doctorId}/{date}', [PasienController::class, 'getAvailableSlots'])->name('available.slots');

        // FEEDBACK PASIEN
        Route::get('/feedback/create/{appointment_id}', [FeedbackController::class, 'create'])->name('feedback.create');
        Route::post('/feedback/{appointment_id}', [FeedbackController::class, 'store'])->name('feedback.store');
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    });
});