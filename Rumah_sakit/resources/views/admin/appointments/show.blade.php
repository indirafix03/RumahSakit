@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Detail Janji Temu</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.appointments.index') }}">Janji Temu</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Janji Temu -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Janji Temu</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Pasien</th>
                                    <td>{{ $appointment->pasien->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email Pasien</th>
                                    <td>{{ $appointment->pasien->email }}</td>
                                </tr>
                                <tr>
                                    <th>Dokter</th>
                                    <td>Dr. {{ $appointment->dokter->name }}</td>
                                </tr>
                                <tr>
                                    <th>Poli</th>
                                    <td>{{ $appointment->dokter->poli->nama_poli ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Tanggal</th>
                                    <td>{{ $appointment->tanggal_booking->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu</th>
                                    <td>
                                        @if($appointment->schedule)
                                            {{ \Carbon\Carbon::parse($appointment->schedule->jam_mulai)->format('H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'selesai' => 'info'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>{{ $appointment->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Keluhan -->
                    <div class="mt-4">
                        <h6>Keluhan Pasien</h6>
                        <div class="border rounded p-3 bg-light">
                            {{ $appointment->keluhan_singkat }}
                        </div>
                    </div>

                    <!-- Alasan Penolakan -->
                    @if($appointment->alasan_reject)
                    <div class="mt-4">
                        <h6>Alasan Penolakan</h6>
                        <div class="border rounded p-3 bg-light">
                            {{ $appointment->alasan_reject }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Rekam Medis -->
            @if($appointment->medicalRecord)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Rekam Medis</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Diagnosis</th>
                                    <td>{{ $appointment->medicalRecord->diagnosis }}</td>
                                </tr>
                                <tr>
                                    <th>Tindakan Medis</th>
                                    <td>{{ $appointment->medicalRecord->tindakan_medis }}</td>
                                </tr>
                                @if($appointment->medicalRecord->catatan)
                                <tr>
                                    <th>Catatan</th>
                                    <td>{{ $appointment->medicalRecord->catatan }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Tanggal Rekam</th>
                                    <td>{{ $appointment->medicalRecord->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Resep Obat -->
                    @if($appointment->medicalRecord->prescriptionItems->count() > 0)
                    <div class="mt-4">
                        <h6>Resep Obat</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Jumlah</th>
                                        <th>Tipe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointment->medicalRecord->prescriptionItems as $item)
                                    <tr>
                                        <td>{{ $item->medicine->nama_obat }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->medicine->tipe_obat === 'keras' ? 'danger' : 'success' }}">
                                                {{ ucfirst($item->medicine->tipe_obat) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <!-- Status Actions -->
            @if($appointment->status === 'pending')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.appointments.updateStatus', $appointment) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-check me-2"></i>Setujui Janji Temu
                        </button>
                    </form>

                    <form action="{{ route('admin.appointments.updateStatus', $appointment) }}" method="POST" id="rejectForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <div class="mb-3">
                            <label for="alasan_reject" class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" id="alasan_reject" name="alasan_reject" 
                                      rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-times me-2"></i>Tolak Janji Temu
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Delete Action -->
            @if(!$appointment->medicalRecord)
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-trash me-2"></i>Hapus Janji Temu</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Hati-hati! Tindakan ini tidak dapat dibatalkan. Janji temu akan dihapus permanen dari sistem.
                    </p>
                    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" 
                                onclick="return confirm('Yakin ingin menghapus janji temu ini?')">
                            <i class="fas fa-trash me-2"></i>Hapus Janji Temu
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection