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
            <!-- TANGGAL HARI INI - Ini akan menampilkan tanggal sesuai waktu server saat ini. -->
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
                                @if(isset($todayApprovedAppointments))
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
                                @if(isset($recentPatients))
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
                                Sesi Praktik Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if(isset($schedulesToday))
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
        <!-- Janji Temu Hari Ini (Detail) -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calendar-day me-2"></i>Antrian Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($todayApprovedAppointments) && $todayApprovedAppointments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($todayApprovedAppointments as $appointment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <!-- MENGAMBIL DATA PASIEN DAN WAKTU DARI VARIABEL -->
                                    <h6 class="mb-1">{{ $appointment->pasien->name ?? 'Pasien Tidak Dikenal' }}</h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->time_slot)->format('H:i') }}
                                        <!-- Jika time_slot tidak ada di Appointment, pastikan menggunakan kolom waktu yang sesuai -->
                                    </p>
                                    <small class="text-muted">{{ Str::limit($appointment->keluhan, 50) }}</small>
                                </div>
                                <div>
                                    <a href="{{ route('dokter.medical-records.create', ['appointment_id' => $appointment->id]) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-file-medical me-1"></i>Mulai
                                    </a>
                                    <a href="{{ route('dokter.appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada janji temu yang disetujui untuk hari ini</p>
                            <small class="text-muted">Pastikan ada janji temu dengan status 'Approved'</small>
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

            <!-- Pasien Terbaru (Recent Medical Records) -->
            <div class="card shadow">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-injured me-2"></i>Rekam Medis Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentPatients) && $recentPatients->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($recentPatients as $record)
                                <a href="{{ route('dokter.medical-records.show', $record->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $record->pasien->name ?? 'Pasien (ID: ' . $record->pasien_id . ')' }}</h6>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($record->created_at)->format('d/m') }}</small>
                                    </div>
                                    <p class="mb-1 small text-muted">
                                        {{ Str::limit($record->diagnosis, 50) }}
                                    </p>
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
    @if(isset($schedulesToday) && $schedulesToday->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-stethoscope me-2"></i>Sesi Praktik Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($schedulesToday as $schedule)
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $schedule->day }}</h5>
                                    <p class="card-text mb-1">
                                        <strong>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</strong>
                                    </p>
                                    <small class="text-muted">{{ $schedule->session_duration }} menit per pasien</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
    border-radius: 12px; /* Ditingkatkan sedikit */
    overflow: hidden;
}
.card-header {
    border-radius: 12px 12px 0 0 !important;
    font-size: 1.1rem;
    padding: 0.75rem 1.25rem;
}
.border-left-warning {
    border-left: 5px solid #f6c23e;
}
.border-left-primary {
    border-left: 5px solid #4e73df;
}
.border-left-success {
    border-left: 5px solid #1cc88a;
}
.border-left-info {
    border-left: 5px solid #36b9cc;
}
.btn-outline-primary, .btn-outline-info, .btn-outline-warning, .btn-outline-secondary {
    transition: all 0.3s ease;
}
.btn-outline-primary:hover, .btn-outline-info:hover, .btn-outline-warning:hover, .btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
/* Style untuk list group janji temu */
.list-group-item:hover {
    background-color: #f7f9fc;
    border-left: 3px solid #4e73df;
}
</style>
@endpush