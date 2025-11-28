@extends('layouts.app')

@section('title', 'Rekam Medis Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4">Rekam Medis Saya</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    @if($medicalRecords->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Dokter</th>
                                        <th>Diagnosis</th>
                                        <th>Tindakan Medis</th>
                                        <th>Resep</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicalRecords as $record)
                                    <tr>
                                        <td>{{ $record->created_at->format('d/m/Y') }}</td>
                                        <td>Dr. {{ $record->dokter->name }}</td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="{{ $record->diagnosis }}">
                                                {{ Str::limit($record->diagnosis, 40) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="{{ $record->tindakan_medis ?? '-' }}">
                                                {{ Str::limit($record->tindakan_medis, 40) ?: '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($record->prescriptions->count() > 0)
                                                <span class="badge bg-success">{{ $record->prescriptions->count() }} obat</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#recordModal{{ $record->id }}">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $medicalRecords->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-medical-alt fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum ada rekam medis</h4>
                            <p class="text-muted">Rekam medis akan tersedia setelah konsultasi dengan dokter.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======================= MODALS ======================= --}}
@foreach($medicalRecords as $record)
<div class="modal fade" id="recordModal{{ $record->id }}" tabindex="-1" aria-labelledby="recordModalLabel{{ $record->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordModalLabel{{ $record->id }}">Detail Rekam Medis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tanggal:</strong> {{ $record->created_at->format('d F Y H:i') }}</p>
                        <p><strong>Dokter:</strong> Dr. {{ $record->dokter->name }}</p>
                        <p><strong>Poli:</strong> {{ $record->dokter->poli->nama_poli ?? 'Tidak ada poli' }}</p>
                        
                        <p class="mt-3"><strong>Diagnosis:</strong></p>
                        <div class="border p-3 rounded bg-light">
                            {{ $record->diagnosis }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tindakan Medis:</strong></p>
                        <div class="border p-3 rounded bg-light">
                            {{ $record->tindakan_medis ?? 'Tidak ada tindakan medis' }}
                        </div>
                        
                        @if($record->catatan)
                        <p class="mt-3"><strong>Catatan Tambahan:</strong></p>
                        <div class="border p-3 rounded bg-light">
                            {{ $record->catatan }}
                        </div>
                        @endif
                    </div>
                </div>
                
                {{-- Informasi Janji Temu --}}
                @if($record->appointment)
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="fw-bold border-bottom pb-2">Informasi Janji Temu</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Tanggal Janji Temu:</strong> {{ $record->appointment->tanggal_booking->format('d F Y') }}</p>
                                <p><strong>Keluhan Awal:</strong></p>
                                <div class="border p-2 rounded bg-light small">
                                    {{ $record->appointment->keluhan_singkat ?? 'Tidak ada keluhan' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status Janji Temu:</strong> 
                                    <span class="badge bg-{{ $record->appointment->status == 'selesai' ? 'success' : 'warning' }}">
                                        {{ ucfirst($record->appointment->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- Resep Obat --}}
                @if($record->prescriptions->count() > 0)
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
                                    @foreach($record->prescriptions as $index => $prescription)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $prescription->medicine->nama_obat ?? 'Obat tidak ditemukan' }}
                                        </td>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Debug modal functionality
    const modalButtons = document.querySelectorAll('[data-bs-toggle="modal"]');
    console.log('Found medical record modal buttons:', modalButtons.length);
});
</script>
@endsection