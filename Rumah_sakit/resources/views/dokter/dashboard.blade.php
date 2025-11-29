@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="page-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Dashboard Dokter</h1>
                <p class="page-subtitle">Selamat datang, Dr. {{ Auth::user()->name }}!</p>
            </div>
            <div class="text-white">
                <p class="mb-0 fw-medium">
                    <i class="fas fa-calendar-day me-2"></i>
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Stats Section -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-warning);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number text-warning">{{ $pendingCount ?? 0 }}</div>
                <div class="stats-label">Janji Temu Pending</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-primary);">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-number text-primary">{{ $todayApprovedAppointments->count() ?? 0 }}</div>
                <div class="stats-label">Janji Temu Hari Ini</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-success);">
                    <i class="fas fa-user-injured"></i>
                </div>
                <div class="stats-number text-success">{{ $recentPatients->count() ?? 0 }}</div>
                <div class="stats-label">Pasien Diperiksa</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-info);">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div class="stats-number text-info">{{ $schedulesToday->count() ?? 0 }}</div>
                <div class="stats-label">Sesi Praktik Hari Ini</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Antrian Hari Ini -->
        <div class="col-lg-6 mb-4">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">
                        <i class="fas fa-calendar-day me-2"></i>Antrian Hari Ini
                        <span class="card-badge">{{ $todayApprovedAppointments->count() }}</span>
                    </h5>
                </div>
                <div class="card-body-custom">
                    @if($todayApprovedAppointments->count() > 0)
                        <div class="list-group">
                            @foreach($todayApprovedAppointments as $appointment)
                            <div class="list-group-item-custom d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-primary">{{ $appointment->pasien->name ?? 'Pasien Tidak Dikenal' }}</h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="fas fa-clock me-1"></i>
                                        @if($appointment->schedule)
                                            {{ \Carbon\Carbon::parse($appointment->schedule->jam_mulai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($appointment->schedule->jam_mulai)->addMinutes($appointment->schedule->durasi)->format('H:i') }}
                                        @else
                                            Waktu tidak tersedia
                                        @endif
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-comment-medical me-1"></i>
                                        {{ Str::limit($appointment->keluhan_singkat, 50) }}
                                    </small>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('dokter.medical-records.create-from-appointment', $appointment->id) }}" 
                                       class="btn btn-success-custom"
                                       data-bs-toggle="tooltip" title="Buat Rekam Medis">
                                        <i class="fas fa-file-medical me-1"></i>Mulai
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h5 class="empty-state-title">Tidak ada janji temu</h5>
                            <p class="empty-state-text">Tidak ada janji temu untuk hari ini. Semua janji temu sudah selesai atau belum ada yang approved.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rekam Medis Terbaru -->
        <div class="col-lg-6 mb-4">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">
                        <i class="fas fa-user-injured me-2"></i>Rekam Medis Terbaru
                        <span class="card-badge">{{ $recentPatients->count() }}</span>
                    </h5>
                </div>
                <div class="card-body-custom">
                    @if($recentPatients->count() > 0)
                        <div class="list-group">
                            @foreach ($recentPatients as $record)
                                <a href="{{ route('dokter.medical-records.show', $record->id) }}" 
                                   class="list-group-item-custom list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $record->pasien->name ?? 'Pasien (ID: ' . $record->pasien_id . ')' }}</h6>
                                            <p class="mb-1 small text-muted">
                                                {{ Str::limit($record->diagnosis ?? 'Belum ada diagnosis', 60) }}
                                            </p>
                                        </div>
                                        <small class="text-muted">{{ $record->created_at->format('d/m') }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="empty-state-title">Belum ada pasien</h5>
                            <p class="empty-state-text">Belum ada pasien yang diperiksa. Data akan muncul setelah membuat rekam medis.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="content-card mb-4">
        <div class="card-header-custom">
            <h5 class="card-title-custom">
                <i class="fas fa-rocket me-2"></i>Akses Cepat
            </h5>
        </div>
        <div class="card-body-custom">
            <div class="row g-3">
                <div class="col-md-6">
                    <a href="{{ route('dokter.schedules.index') }}" class="btn btn-outline-custom">
                        <i class="fas fa-calendar-alt me-2"></i>Kelola Jadwal
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('dokter.appointments.index') }}" class="btn btn-outline-custom">
                        <i class="fas fa-list me-2"></i>Lihat Semua Janji
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('dokter.medical-records.index') }}" class="btn btn-outline-custom">
                        <i class="fas fa-file-medical me-2"></i>Semua Rekam Medis
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('dokter.feedback.index') }}" class="btn btn-outline-custom">
                        <i class="fas fa-star me-2"></i>Lihat Feedback
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-custom">
                        <i class="fas fa-user me-2"></i>Profil Saya
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Praktik Hari Ini -->
    @if($schedulesToday->count() > 0)
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="card-title-custom">
                <i class="fas fa-stethoscope me-2"></i>Sesi Praktik Hari Ini
                <span class="card-badge">{{ $schedulesToday->count() }} sesi</span>
            </h5>
        </div>
        <div class="card-body-custom">
            <div class="row">
                @foreach ($schedulesToday as $schedule)
                <div class="col-md-4 mb-3">
                    <div class="schedule-card">
                        <div class="schedule-day">{{ ucfirst($schedule->hari) }}</div>
                        <div class="schedule-time">
                            {{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($schedule->jam_mulai)->addMinutes($schedule->durasi)->format('H:i') }}
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-stopwatch me-1"></i>
                                {{ $schedule->durasi }} menit per sesi
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="card-title-custom">
                <i class="fas fa-stethoscope me-2"></i>Sesi Praktik Hari Ini
            </h5>
        </div>
        <div class="card-body-custom">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h5 class="empty-state-title">Tidak ada jadwal praktik</h5>
                <p class="empty-state-text">Tidak ada jadwal praktik untuk hari ini. Anda bisa menambahkan jadwal melalui menu Kelola Jadwal.</p>
                <div class="mt-3">
                    <a href="{{ route('dokter.schedules.index') }}" class="btn btn-primary-custom">
                        <i class="fas fa-plus me-1"></i> Tambah Jadwal
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush