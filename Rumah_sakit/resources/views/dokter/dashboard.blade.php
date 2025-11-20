@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Dashboard Dokter</h1>
            <p class="text-muted">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
        <div class="text-end">
            <p class="mb-0"><strong>Hari ini:</strong> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
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
                                @if(isset($todayApprovedAppointments) && $todayApprovedAppointments->count() > 0)
                                    {{ $todayApprovedAppointments->count() }}
                                @else
                                    0
                                @endif
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
                                @if(isset($recentPatients) && $recentPatients->count() > 0)
                                    {{ $recentPatients->count() }}
                                @else
                                    0
                                @endif
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
                                Jadwal Praktik Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if(isset($schedulesToday) && $schedulesToday->count() > 0)
                                    {{ $schedulesToday->count() }}
                                @else
                                    0
                                @endif
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
        <!-- Janji Temu Hari Ini -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calendar-day me-2"></i>Janji Temu Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($todayApprovedAppointments) && $todayApprovedAppointments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($todayApprovedAppointments as $appointment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Pasien Sample</h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="fas fa-clock me-1"></i>08:00
                                    </p>
                                    <small class="text-muted">Keluhan pasien...</small>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-success" disabled>
                                        <i class="fas fa-play me-1"></i>Mulai
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" disabled>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada janji temu untuk hari ini</p>
                            <small class="text-muted">Data akan muncul setelah ada janji temu</small>
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
                        <i class="fas fa-rocket me-2"></i>Quick Links
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
                                <i class="fas fa-list me-2"></i>Lihat Antrian
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('dokter.medical-records.index') }}" class="btn btn-outline-warning w-100 text-start">
                                <i class="fas fa-file-medical me-2"></i>Rekam Medis
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
                        <i class="fas fa-user-injured me-2"></i>Pasien Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentPatients) && $recentPatients->count() > 0)
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Pasien Sample 1</h6>
                                    <small class="text-muted">19/11</small>
                                </div>
                                <p class="mb-1 small text-muted">
                                    Diagnosis sample untuk pasien...
                                </p>
                            </div>
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

    <!-- Jadwal Praktik Hari Ini -->
    @if(isset($schedulesToday) && $schedulesToday->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-stethoscope me-2"></i>Jadwal Praktik Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Senin</h5>
                                    <p class="card-text">
                                        <strong>08:00 - 16:00</strong>
                                    </p>
                                    <small class="text-muted">30 menit per pasien</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal Mulai Konsultasi -->
<div class="modal fade" id="konsultasiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mulai Konsultasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Fitur ini akan tersedia setelah data janji temu tersedia.</p>
                <div id="patientInfo"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 10px;
}
.card-header {
    border-radius: 10px 10px 0 0 !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e;
}
.border-left-primary {
    border-left: 4px solid #4e73df;
}
.border-left-success {
    border-left: 4px solid #1cc88a;
}
.border-left-info {
    border-left: 4px solid #36b9cc;
}
.btn-outline-primary, .btn-outline-info, .btn-outline-warning, .btn-outline-secondary {
    transition: all 0.3s ease;
}
.btn-outline-primary:hover, .btn-outline-info:hover, .btn-outline-warning:hover, .btn-outline-secondary:hover {
    transform: translateY(-2px);
}
</style>
@endpush