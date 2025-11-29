@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="min-h-screen">  
    <!-- Page Content -->
    <main>
        <!-- Page Header -->
        <div class="page-header">
            <div class="container position-relative">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title">Dashboard Pasien</h1>
                        <p class="page-subtitle mb-0">Selamat datang kembali, {{ Auth::user()->name }}!</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary-custom">
                            <i class="fas fa-plus-circle me-2"></i>Buat Janji Temu Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <!-- Menunggu Validasi -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stats-card stats-warning">
                        <div class="stats-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $pendingAppointments ?? 0 }}</div>
                            <div class="stats-label">Menunggu Validasi</div>
                        </div>
                    </div>
                </div>

                <!-- Janji Hari Ini -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stats-card stats-success">
                        <div class="stats-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $todayAppointments ?? 0 }}</div>
                            <div class="stats-label">Janji Hari Ini</div>
                        </div>
                    </div>
                </div>

                <!-- Resep Siap -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stats-card stats-info">
                        <div class="stats-icon">
                            <i class="fas fa-prescription-bottle"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $readyPrescriptions ?? 0 }}</div>
                            <div class="stats-label">Resep Siap</div>
                            <div class="stats-subtext">Siap diambil</div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Rekam Medis -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stats-card stats-primary">
                        <div class="stats-icon">
                            <i class="fas fa-notes-medical"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $totalMedicalRecords ?? 0 }}</div>
                            <div class="stats-label">Total Rekam Medis</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifikasi Resep Siap -->
            @if($readyPrescriptions > 0)
            <div class="alert alert-success-custom alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-prescription-bottle me-3 fs-4"></i>
                    <div class="flex-grow-1">
                        <strong>Resep Siap!</strong> Anda memiliki <strong>{{ $readyPrescriptions }} resep</strong> yang siap diambil.
                    </div>
                    <a href="{{ route('pasien.medical-records.index') }}" class="btn btn-sm btn-success me-2">
                        Lihat Detail
                    </a>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

            <!-- Latest Appointment -->
            @if(isset($latestAppointment) && $latestAppointment)
            <div class="section-card mb-4">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-check me-2"></i>Janji Temu Terakhir
                    </h2>
                    @php
                        $status_badge = [
                            'approved' => 'success',
                            'pending' => 'warning', 
                            'rejected' => 'danger',
                            'selesai' => 'info'
                        ];
                        $badge_class = $status_badge[$latestAppointment->status] ?? 'secondary';
                    @endphp
                    <span class="badge-custom badge-{{ $badge_class }}">
                        {{ ucfirst($latestAppointment->status) }}
                    </span>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Dokter</label>
                                <p class="info-value">Dr. {{ $latestAppointment->dokter->name ?? 'Tidak diketahui' }}</p>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Tanggal</label>
                                <p class="info-value">{{ $latestAppointment->tanggal_booking->format('d F Y') }}</p>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Waktu</label>
                                <p class="info-value">
                                    @if($latestAppointment->schedule)
                                        {{ \Carbon\Carbon::parse($latestAppointment->schedule->jam_mulai)->format('H:i') }} 
                                        - {{ \Carbon\Carbon::parse($latestAppointment->schedule->jam_selesai)->format('H:i') }}
                                    @else
                                        Tidak tersedia
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">Poli</label>
                                <p class="info-value">{{ $latestAppointment->dokter->poli->nama_poli ?? 'N/A' }}</p>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Hari</label>
                                <p class="info-value">
                                    @if($latestAppointment->schedule)
                                        {{ ucfirst($latestAppointment->schedule->hari) }}
                                    @else
                                        Tidak tersedia
                                    @endif
                                </p>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Keluhan</label>
                                <p class="info-value">{{ $latestAppointment->keluhan_singkat }}</p>
                            </div>
                            @if($latestAppointment->alasan_reject)
                                <div class="info-group">
                                    <label class="info-label text-danger">Alasan Ditolak</label>
                                    <p class="info-value text-danger">{{ $latestAppointment->alasan_reject }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('pasien.appointments.show', $latestAppointment->id) }}" class="btn btn-info-custom">
                                <i class="fas fa-eye me-2"></i>Detail Janji Temu
                            </a>
                            
                            <div class="d-flex align-items-center gap-3">
                                {{-- TOMBOL FEEDBACK - HANYA TAMPIL JIKA STATUS SELESAI DAN BELUM ADA FEEDBACK --}}
                                @if($latestAppointment->status === 'selesai' && !$latestAppointment->feedback)
                                    <a href="{{ route('pasien.feedback.create', $latestAppointment->id) }}" class="btn btn-success-custom">
                                        <i class="fas fa-star me-2"></i>Beri Feedback
                                    </a>
                                @endif
                                
                                {{-- INFO JIKA SUDAH MEMBERIKAN FEEDBACK --}}
                                @if($latestAppointment->status === 'selesai' && $latestAppointment->feedback)
                                    <span class="badge-custom badge-success">
                                        <i class="fas fa-check me-1"></i> Sudah memberikan feedback
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="section-card mb-4">
                <div class="section-body text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada janji temu</h5>
                    <p class="text-muted">Mulai buat janji temu pertama Anda</p>
                    <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary-custom mt-3">
                        <i class="fas fa-plus-circle me-2"></i>Buat Janji Temu Pertama
                    </a>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h2>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-4 col-lg-3">
                            <a href="{{ route('pasien.appointments.create') }}" class="quick-action-card">
                                <div class="quick-action-icon bg-primary">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Buat Janji Temu</h6>
                                    <p>Buat janji temu baru</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <a href="{{ route('pasien.appointments.index') }}" class="quick-action-card">
                                <div class="quick-action-icon bg-info">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Lihat Janji Temu</h6>
                                    <p>Lihat semua janji temu</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <a href="{{ route('pasien.medical-records.index') }}" class="quick-action-card">
                                <div class="quick-action-icon bg-success">
                                    <i class="fas fa-file-medical"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Rekam Medis</h6>
                                    <p>Lihat riwayat kesehatan</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <a href="{{ route('profile.edit') }}" class="quick-action-card">
                                <div class="quick-action-icon bg-warning">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Edit Profil</h6>
                                    <p>Perbarui data diri</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <a href="{{ route('pasien.prescriptions.index') }}" class="quick-action-card">
                                <div class="quick-action-icon bg-info">
                                    <i class="fas fa-prescription-bottle"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Lihat Resep</h6>
                                    <p>Resep obat Anda</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <a href="{{ route('pasien.feedback.index') }}" class="quick-action-card">
                                <div class="quick-action-icon bg-secondary">
                                    <i class="fas fa-comment-medical"></i>
                                </div>
                                <div class="quick-action-text">
                                    <h6>Feedback Saya</h6>
                                    <p>Ulasan dan penilaian</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
:root {
    --primary-text: #094fa4;
    --secondary-color: #0065c1;
    --card-color: #009ee5;
    --background-color: #52bcec;
    --light-bg: #f8f9fa;
    --dark-text: #2c3e50;
    --light-text: #7f8c8d;
    --white: #ffffff;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #3b82f6;
}

body {
    font-family: 'Oswald', sans-serif;
    color: var(--primary-text);
    line-height: 1.6;
    font-weight: 400;
    background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
    min-height: 100vh;
}

.admin-navbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 1rem 0;
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.8rem;
    background: linear-gradient(135deg, var(--primary-text) 0%, var(--secondary-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 0.5px;
}

.nav-link {
    font-weight: 500;
    color: var(--primary-text);
    margin: 0 0.5rem;
    letter-spacing: 0.5px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    transition: width 0.3s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 100%;
}

.nav-link.active {
    color: var(--secondary-color);
    font-weight: 600;
}

.btn-logout {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    color: var(--white);
    border: none;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
}

.page-header {
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    color: var(--white);
    padding: 2rem 0;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,50 1000,100 0,100"/></svg>');
    background-size: cover;
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    position: relative;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 300;
}

/* Stats Cards */
.stats-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 101, 193, 0.1);
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 101, 193, 0.15);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
    color: white;
}

.stats-primary .stats-icon { background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%); }
.stats-success .stats-icon { background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%); }
.stats-warning .stats-icon { background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%); }
.stats-info .stats-icon { background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%); }

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--dark-text);
}

.stats-label {
    color: var(--light-text);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.stats-subtext {
    color: var(--light-text);
    font-size: 0.75rem;
    font-style: italic;
}

/* Section Cards */
.section-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 
        0 8px 32px rgba(0, 101, 193, 0.1),
        0 2px 8px rgba(0, 101, 193, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.section-card:hover {
    box-shadow: 
        0 12px 40px rgba(0, 101, 193, 0.15),
        0 4px 12px rgba(0, 101, 193, 0.1);
}

.section-header {
    background: linear-gradient(135deg, rgba(0, 101, 193, 0.05) 0%, rgba(0, 158, 229, 0.05) 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    color: var(--primary-text);
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
}

.section-body {
    padding: 2rem;
}

/* Button Styles */
.btn-primary-custom {
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    color: var(--white);
    border: none;
    padding: 0.8rem 2rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 101, 193, 0.2);
    text-transform: uppercase;
    font-size: 0.9rem;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 101, 193, 0.3);
    color: var(--white);
}

.btn-info-custom {
    background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
    color: var(--white);
    border: none;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}

.btn-info-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    color: var(--white);
}

.btn-success-custom {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    color: var(--white);
    border: none;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
}

.btn-success-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    color: var(--white);
}

/* Alert Styles */
.alert-success-custom {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 12px;
    color: var(--success-color);
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

/* Badge Styles */
.badge-custom {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.badge-warning {
    background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
    color: white;
}

.badge-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    color: white;
}

.badge-danger {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    color: white;
}

.badge-info {
    background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
    color: white;
}

.badge-secondary {
    background: linear-gradient(135deg, var(--light-text) 0%, #6b7280 100%);
    color: white;
}

/* Info Group Styles */
.info-group {
    margin-bottom: 1rem;
}

.info-label {
    font-weight: 600;
    color: var(--primary-text);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.info-value {
    color: var(--dark-text);
    font-size: 1rem;
    margin: 0;
}

/* Quick Action Cards */
.quick-action-card {
    display: block;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
    text-decoration: none;
    color: inherit;
    height: 100%;
}

.quick-action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 101, 193, 0.15);
    text-decoration: none;
    color: inherit;
}

.quick-action-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.quick-action-text h6 {
    font-weight: 600;
    color: var(--primary-text);
    margin-bottom: 0.5rem;
}

.quick-action-text p {
    color: var(--light-text);
    font-size: 0.85rem;
    margin: 0;
}

.btn-close-custom {
    background: none;
    border: none;
    font-size: 1.2rem;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.btn-close-custom:hover {
    opacity: 1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.3rem;
    }
    
    .btn-primary-custom {
        padding: 0.7rem 1.5rem;
        font-size: 0.85rem;
    }
    
    .section-body {
        padding: 1.5rem;
    }
    
    .stats-card {
        padding: 1rem;
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
        margin-right: 0.75rem;
    }
    
    .stats-number {
        font-size: 1.5rem;
    }
}
</style>
@endsection