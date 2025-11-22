@extends('layouts.app')

@section('title', 'Detail Janji Temu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Detail Janji Temu</h1>
                <a href="{{ route('pasien.appointments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Janji Temu</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Dokter:</strong> Dr. {{ $appointment->dokter->name }}</p>
                            <p><strong>Tanggal:</strong> {{ $appointment->tanggal_booking->format('d F Y') }}</p>
                            <p><strong>Waktu:</strong> {{ $appointment->schedule->jam_mulai }} - {{ $appointment->schedule->jam_selesai }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Hari:</strong> {{ $appointment->schedule->hari }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge badge-{{ $appointment->status == 'approved' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'selesai' ? 'info' : 'danger')) }}">
                                    {{ $appointment->status }}
                                </span>
                            </p>
                            <p><strong>Dibuat:</strong> {{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Keluhan:</strong></p>
                            <div class="border p-3 rounded bg-light">
                                {{ $appointment->keluhan_singkat }}
                            </div>
                        </div>
                    </div>

                    @if($appointment->alasan_reject)
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Alasan Penolakan:</strong></p>
                            <div class="border p-3 rounded bg-light">
                                {{ $appointment->alasan_reject }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($appointment->status == 'pending')
                            <a href="{{ route('pasien.appointments.edit', $appointment->id) }}" class="btn btn-warning btn-block">
                                <i class="fas fa-edit"></i> Edit Janji Temu
                            </a>
                            
                            <form action="{{ route('pasien.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-block" 
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan janji temu ini?')">
                                    <i class="fas fa-times"></i> Batalkan Janji Temu
                                </button>
                            </form>

                            <form action="{{ route('pasien.appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-block" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus janji temu ini?')">
                                    <i class="fas fa-trash"></i> Hapus Janji Temu
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('pasien.appointments.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-list"></i> Kembali ke Daftar
                        </a>
                    </div>

                    @if($appointment->status != 'pending')
                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Hanya janji temu dengan status "pending" yang dapat diedit atau dibatalkan.
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            @if($appointment->medicalRecord)
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Rekam Medis</h5>
                </div>
                <div class="card-body">
                    <p><strong>Diagnosis:</strong> {{ $appointment->medicalRecord->diagnosis }}</p>
                    <p><strong>Tindakan Medis:</strong> {{ $appointment->medicalRecord->tindakan_medis ?? '-' }}</p>
                    <a href="{{ route('pasien.medical-records.index') }}" class="btn btn-sm btn-info">
                        Lihat Detail Rekam Medis
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection