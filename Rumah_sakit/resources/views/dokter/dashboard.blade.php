@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Dashboard Dokter</h1>
            <p class="text-muted">Selamat datang, Dr. {{ Auth::user()->name }}!</p>
        </div>
        <div class="text-end">
            <p class="mb-0"><strong>Hari ini:</strong> {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <!-- Janji Temu Pending -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Janji Temu Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $pendingCount ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Janji Temu Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Janji Temu Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $todayApprovedAppointments->count() ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pasien -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pasien Diperiksa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $recentPatients->count() ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Praktik -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sesi Praktik Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $schedulesToday->count() ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Janji Temu Hari Ini (Detail) -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calendar-day me-2"></i>Antrian Hari Ini
                        <span class="badge bg-light text-primary ms-2">{{ $todayApprovedAppointments->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($todayApprovedAppointments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($todayApprovedAppointments as $appointment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
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
                                       class="btn btn-sm btn-success"
                                       data-bs-toggle="tooltip" title="Buat Rekam Medis">
                                        <i class="fas fa-file-medical me-1"></i>Mulai
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada janji temu untuk hari ini</p>
                            <small class="text-muted">Semua janji temu sudah selesai atau belum ada yang approved</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Links & Recent Patients -->
        <div class="col-lg-6">
            <!-- Quick Links -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-rocket me-2"></i>Akses Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('dokter.schedules.index') }}" class="btn btn-outline-primary w-100 text-start">
                                <i class="fas fa-calendar-alt me-2"></i>Kelola Jadwal
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('dokter.appointments.index') }}" class="btn btn-outline-info w-100 text-start">
                                <i class="fas fa-list me-2"></i>Lihat Semua Janji
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('dokter.medical-records.index') }}" class="btn btn-outline-warning w-100 text-start">
                                <i class="fas fa-file-medical me-2"></i>Semua Rekam Medis
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100 text-start">
                                <i class="fas fa-user me-2"></i>Profil Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pasien Terbaru -->
            <div class="card shadow">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-injured me-2"></i>Rekam Medis Terbaru
                        <span class="badge bg-light text-info ms-2">{{ $recentPatients->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($recentPatients->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($recentPatients as $record)
                                <a href="{{ route('dokter.medical-records.show', $record->id) }}" 
                                   class="list-group-item list-group-item-action">
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
                        <div class="text-center py-3">
                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Belum ada pasien yang diperiksa</p>
                            <small class="text-muted">Data akan muncul setelah membuat rekam medis</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Praktik Hari Ini (DETAIL) -->
    @if($schedulesToday->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-stethoscope me-2"></i>Sesi Praktik Hari Ini
                        <span class="badge bg-light text-dark ms-2">{{ $schedulesToday->count() }} sesi</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($schedulesToday as $schedule)
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-warning text-uppercase">
                                        {{ ucfirst($schedule->hari) }}
                                    </h5>
                                    <div class="my-3">
                                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                        <p class="card-text mb-1">
                                            <strong class="h5">
                                                {{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($schedule->jam_mulai)->addMinutes($schedule->durasi)->format('H:i') }}
                                            </strong>
                                        </p>
                                    </div>
                                    <div class="border-top pt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-stopwatch me-1"></i>
                                            {{ $schedule->durasi }} menit per sesi
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-light text-dark py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-stethoscope me-2"></i>Sesi Praktik Hari Ini
                    </h6>
                </div>
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada jadwal praktik untuk hari ini</p>
                    <small class="text-muted">Anda bisa menambahkan jadwal melalui menu Kelola Jadwal</small>
                    <div class="mt-3">
                        <a href="{{ route('dokter.schedules.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.container-fluid {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}
.card-header {
    border-radius: 12px 12px 0 0 !important;
    font-size: 1.1rem;
    padding: 0.75rem 1.25rem;
}
.border-left-warning { border-left: 5px solid #f6c23e; }
.border-left-primary { border-left: 5px solid #4e73df; }
.border-left-success { border-left: 5px solid #1cc88a; }
.border-left-info { border-left: 5px solid #36b9cc; }

.btn-outline-primary:hover, .btn-outline-info:hover, 
.btn-outline-warning:hover, .btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.list-group-item:hover {
    background-color: #f7f9fc;
    border-left: 3px solid #4e73df;
    transition: all 0.3s ease;
}

.card.border-warning:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(255, 193, 7, 0.2);
    transition: all 0.3s ease;
}
</style>
@endpush

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