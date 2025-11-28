@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4">Dashboard Pasien</h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Menunggu Validasi -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Validasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingAppointments ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Janji Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Janji Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayAppointments ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resep Siap -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Resep Siap</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $readyPrescriptions ?? 0 }}</div>
                            <small class="text-muted">Siap diambil</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-prescription-bottle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Rekam Medis -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Rekam Medis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMedicalRecords ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-notes-medical fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Appointment -->
    @if(isset($latestAppointment) && $latestAppointment)
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Janji Temu Terakhir</h6>
                    @php
                        $status_badge = [
                            'approved' => 'success',
                            'pending' => 'warning', 
                            'rejected' => 'danger',
                            'selesai' => 'info'
                        ];
                        $badge_class = $status_badge[$latestAppointment->status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $badge_class }}">
                        {{ ucfirst($latestAppointment->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Dokter:</strong> Dr. {{ $latestAppointment->dokter->name ?? 'Tidak diketahui' }}</p>
                            <p><strong>Tanggal:</strong> {{ $latestAppointment->tanggal_booking->format('d F Y') }}</p>
                            <p><strong>Waktu:</strong> 
                                @if($latestAppointment->schedule)
                                    {{ \Carbon\Carbon::parse($latestAppointment->schedule->jam_mulai)->format('H:i') }} 
                                    - {{ \Carbon\Carbon::parse($latestAppointment->schedule->jam_selesai)->format('H:i') }}
                                @else
                                    Tidak tersedia
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Poli:</strong> {{ $latestAppointment->dokter->poli->nama_poli ?? 'N/A' }}</p>
                            <p><strong>Hari:</strong> 
                                @if($latestAppointment->schedule)
                                    {{ ucfirst($latestAppointment->schedule->hari) }}
                                @else
                                    Tidak tersedia
                                @endif
                            </p>
                            <p><strong>Keluhan:</strong> {{ $latestAppointment->keluhan_singkat }}</p>
                            @if($latestAppointment->alasan_reject)
                                <p><strong>Alasan Ditolak:</strong> <span class="text-danger">{{ $latestAppointment->alasan_reject }}</span></p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <a href="{{ route('pasien.appointments.show', $latestAppointment->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Detail Janji Temu
                        </a>
                        
                        {{-- TOMBOL FEEDBACK - HANYA TAMPIL JIKA STATUS SELESAI DAN BELUM ADA FEEDBACK --}}
                        @if($latestAppointment->status === 'selesai' && !$latestAppointment->feedback)
                            <a href="{{ route('pasien.feedback.create', $latestAppointment->id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-star me-1"></i> Beri Feedback
                            </a>
                        @endif
                        
                        {{-- INFO JIKA SUDAH MEMBERIKAN FEEDBACK --}}
                        @if($latestAppointment->status === 'selesai' && $latestAppointment->feedback)
                            <span class="badge bg-success ms-2">
                                <i class="fas fa-check me-1"></i> Sudah memberikan feedback
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada janji temu</h5>
                    <p class="text-muted">Mulai buat janji temu pertama Anda</p>
                    <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Buat Janji Temu
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Notifikasi Resep Siap --}}
    @if($readyPrescriptions > 0)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-prescription-bottle me-2"></i>
        Anda memiliki <strong>{{ $readyPrescriptions }} resep</strong> yang siap diambil!
        <a href="{{ route('pasien.medical-records.index') }}" class="alert-link">Lihat detail</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus-circle"></i> Buat Janji Temu
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pasien.appointments.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-list"></i> Lihat Janji Temu
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pasien.medical-records.index') }}" class="btn btn-success btn-block">
                                <i class="fas fa-file-medical"></i> Rekam Medis
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-user-edit"></i> Edit Profil
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pasien.prescriptions.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-prescription-bottle"></i> Lihat Resep
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pasien.feedback.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-comment-medical"></i> Feedback Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection