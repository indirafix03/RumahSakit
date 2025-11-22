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
                                <thead class="thead-light">
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
                                            <span data-toggle="tooltip" title="{{ $record->diagnosis }}">
                                                {{ Str::limit($record->diagnosis, 40) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span data-toggle="tooltip" title="{{ $record->tindakan_medis ?? '-' }}">
                                                {{ Str::limit($record->tindakan_medis, 40) ?: '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($record->prescriptions->count() > 0)
                                                <span class="badge badge-success">{{ $record->prescriptions->count() }} obat</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#recordModal{{ $record->id }}">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="recordModal{{ $record->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Rekam Medis</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Tanggal:</strong> {{ $record->created_at->format('d F Y') }}</p>
                                                            <p><strong>Dokter:</strong> Dr. {{ $record->dokter->name }}</p>
                                                            <p><strong>Diagnosis:</strong></p>
                                                            <div class="border p-3 rounded">
                                                                {{ $record->diagnosis }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Tindakan Medis:</strong></p>
                                                            <div class="border p-3 rounded">
                                                                {{ $record->tindakan_medis ?? '-' }}
                                                            </div>
                                                            <p class="mt-3"><strong>Catatan:</strong></p>
                                                            <div class="border p-3 rounded">
                                                                {{ $record->catatan ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($record->prescriptions->count() > 0)
                                                    <div class="row mt-4">
                                                        <div class="col-12">
                                                            <h6 class="font-weight-bold">Resep Obat:</h6>
                                                            <div class="table-responsive">
                                                                <table class="table table-sm table-bordered">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th>Nama Obat</th>
                                                                            <th>Jumlah</th>
                                                                            <th>Instruksi</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($record->prescriptions as $prescription)
                                                                        <tr>
                                                                            <td>{{ $prescription->medicine->name }}</td>
                                                                            <td>{{ $prescription->quantity }}</td>
                                                                            <td>{{ $prescription->instructions ?? '-' }}</td>
                                                                            <td>
                                                                                <span class="badge badge-{{ $prescription->status == 'ready' ? 'success' : ($prescription->status == 'taken' ? 'info' : 'warning') }}">
                                                                                    {{ $prescription->status }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
@endsection

@section('scripts')
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
@endsection