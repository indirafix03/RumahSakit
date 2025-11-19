<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Auth\RegisteredUserController;

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
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Poli Management
    Route::get('/polis', [PoliController::class, 'index'])->name('polis.index');
    Route::get('/polis/create', [PoliController::class, 'create'])->name('polis.create');
    Route::post('/polis', [PoliController::class, 'store'])->name('polis.store');
    Route::get('/polis/{poli}/edit', [PoliController::class, 'edit'])->name('polis.edit');
    Route::put('/polis/{poli}', [PoliController::class, 'update'])->name('polis.update');
    Route::delete('/polis/{poli}', [PoliController::class, 'destroy'])->name('polis.destroy');
    
    // Medicine Management
    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
    Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create');
    Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store');
    Route::get('/medicines/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
    Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
    
    // Appointments
    Route::get('/appointments', function () { return view('admin.appointments.index'); })->name('appointments.index');
});

// Dokter Routes  
Route::middleware(['auth', 'verified', 'dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedules', function () { return view('dokter.schedules.index'); })->name('schedules.index');
    Route::get('/appointments', function () { return view('dokter.appointments.index'); })->name('appointments.index');
    Route::get('/medical-records', function () { return view('dokter.medical-records.index'); })->name('medical-records.index');
});

// Pasien Routes
Route::middleware(['auth', 'verified', 'pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', function () { return view('pasien.appointments.index'); })->name('appointments.index');
    Route::get('/medical-records', function () { return view('pasien.medical-records.index'); })->name('medical-records.index');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);
    
});
require __DIR__.'/auth.php';