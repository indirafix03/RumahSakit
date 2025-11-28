@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detail Rekam Medis</h1>
        <a href="{{ route('dokter.medical-records.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Informasi Rekam Medis</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Pasien:</strong>
                            <p>{{ $medicalRecord->pasien->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal:</strong>
                            <p>{{ $medicalRecord->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Diagnosis:</strong>
                        <p class="border p-3 rounded bg-light">{{ $medicalRecord->diagnosis }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Tindakan Medis:</strong>
                        <p class="border p-3 rounded bg-light">{{ $medicalRecord->tindakan_medis }}</p>
                    </div>

                    @if($medicalRecord->catatan)
                    <div class="mb-3">
                        <strong>Catatan Tambahan:</strong>
                        <p class="border p-3 rounded bg-light">{{ $medicalRecord->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Resep Obat -->
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-prescription me-2"></i>Resep Obat</h5>
                </div>
                <div class="card-body">
                    @if($medicalRecord->prescriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicalRecord->prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $prescription->medicine->nama_obat }}</td>
                                        <td>{{ $prescription->quantity }}</td>
                                        <td>
                                            <span class="badge bg-{{ $prescription->status == 'pending' ? 'warning' : 'success' }}">
                                                {{ ucfirst($prescription->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Tidak ada resep obat.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Informasi Pasien -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-user-injured me-2"></i>Informasi Pasien</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-3x text-info"></i>
                        </div>
                        <h6>{{ $medicalRecord->pasien->name }}</h6>
                        <p class="text-muted small mb-1">{{ $medicalRecord->pasien->email }}</p>
                        <p class="text-muted small">
                            Terdaftar: {{ $medicalRecord->pasien->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informasi Janji Temu -->
            <div class="card mt-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Informasi Janji Temu</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Tanggal:</strong><br>
                        {{ $medicalRecord->appointment->tanggal_booking->format('d/m/Y') }}
                    </p>
                    <p class="mb-2">
                        <strong>Keluhan:</strong><br>
                        {{ $medicalRecord->appointment->keluhan_singkat }}
                    </p>
                    <p class="mb-0">
                        <strong>Status:</strong><br>
                        <span class="badge bg-success">Selesai</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection