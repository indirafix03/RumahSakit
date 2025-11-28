@extends('layouts.app')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4">Detail Rekam Medis</h1>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tanggal:</strong> {{ $medicalRecord->created_at->format('d F Y H:i') }}</p>
                    <p><strong>Dokter:</strong> Dr. {{ $medicalRecord->dokter->name }}</p>
                    <p><strong>Poli:</strong> {{ $medicalRecord->dokter->poli->nama_poli ?? 'Tidak ada poli' }}</p>
                    
                    <p class="mt-3"><strong>Diagnosis:</strong></p>
                    <div class="border p-3 rounded bg-light">
                        {{ $medicalRecord->diagnosis }}
                    </div>
                </div>
                <div class="col-md-6">
                    <p><strong>Tindakan Medis:</strong></p>
                    <div class="border p-3 rounded bg-light">
                        {{ $medicalRecord->tindakan_medis ?? 'Tidak ada tindakan medis' }}
                    </div>
                    
                    @if($medicalRecord->catatan)
                    <p class="mt-3"><strong>Catatan Tambahan:</strong></p>
                    <div class="border p-3 rounded bg-light">
                        {{ $medicalRecord->catatan }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Informasi Janji Temu --}}
            @if($medicalRecord->appointment)
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="fw-bold border-bottom pb-2">Informasi Janji Temu</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tanggal Janji Temu:</strong> {{ $medicalRecord->appointment->tanggal_booking->format('d F Y') }}</p>
                            <p><strong>Keluhan Awal:</strong></p>
                            <div class="border p-2 rounded bg-light small">
                                {{ $medicalRecord->appointment->keluhan_singkat ?? 'Tidak ada keluhan' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status Janji Temu:</strong> 
                                <span class="badge bg-{{ $medicalRecord->appointment->status == 'selesai' ? 'success' : 'warning' }}">
                                    {{ ucfirst($medicalRecord->appointment->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Resep Obat --}}
            @if($medicalRecord->prescriptions->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="fw-bold border-bottom pb-2">Resep Obat</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Obat</th>
                                    <th>Jumlah</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicalRecord->prescriptions as $index => $prescription)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $prescription->medicine->nama_obat ?? 'Obat tidak ditemukan' }}</td>
                                    <td>{{ $prescription->quantity }}</td>
                                    <td>
                                        <span class="badge bg-{{ ($prescription->medicine->tipe_obat ?? '') == 'keras' ? 'danger' : 'success' }}">
                                            {{ ucfirst($prescription->medicine->tipe_obat ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $prescription->status == 'ready' ? 'success' : ($prescription->status == 'taken' ? 'info' : 'warning') }}">
                                            @if($prescription->status == 'ready')
                                                Siap Diambil
                                            @elseif($prescription->status == 'taken')
                                                Sudah Diambil
                                            @else
                                                Menunggu
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Tidak ada resep obat untuk rekam medis ini.
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('pasien.medical-records.index') }}" class="btn btn-secondary">
                <i class="fas fa-chevron-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
