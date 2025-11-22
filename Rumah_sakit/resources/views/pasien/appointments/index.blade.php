@extends('layouts.app')

@section('title', 'Janji Temu Saya')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Janji Temu Saya</h1>
        <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Janji Temu Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($appointments->count() > 0)
        <div class="card shadow">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Dokter</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->tanggal_booking->format('d/m/Y') }}</td>
                            <td>{{ $appointment->schedule->hari }}</td>
                            <td>{{ $appointment->schedule->jam_mulai }} - {{ $appointment->schedule->jam_selesai }}</td>
                            <td>Dr. {{ $appointment->dokter->name }}</td>

                            <td>
                                <span data-toggle="tooltip" title="{{ $appointment->keluhan_singkat }}">
                                    {{ Str::limit($appointment->keluhan_singkat, 30) }}
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-{{ 
                                    $appointment->status == 'approved' ? 'success' : 
                                    ($appointment->status == 'pending' ? 'warning' : 
                                    ($appointment->status == 'selesai' ? 'info' : 'danger')) 
                                }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm" role="group">

                                    {{-- Detail --}}
                                    <button class="btn btn-info" data-toggle="modal" data-target="#modalDetail{{ $appointment->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    {{-- Edit & Cancel hanya jika pending --}}
                                    @if($appointment->status == 'pending')

                                        <a href="{{ route('pasien.appointments.edit', $appointment->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('pasien.appointments.cancel', $appointment->id) }}" 
                                              method="POST"
                                              onsubmit="return confirm('Batalkan janji temu ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>

                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- ======================= MODAL DETAIL ======================= --}}
                        <div class="modal fade" id="modalDetail{{ $appointment->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Janji Temu</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p><strong>Tanggal:</strong> {{ $appointment->tanggal_booking->format('d F Y') }}</p>
                                                <p><strong>Hari:</strong> {{ $appointment->schedule->hari }}</p>
                                                <p><strong>Waktu:</strong> {{ $appointment->schedule->jam_mulai }} - {{ $appointment->schedule->jam_selesai }}</p>
                                            </div>

                                            <div class="col-md-6">
                                                <p><strong>Dokter:</strong> Dr. {{ $appointment->dokter->name }}</p>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge badge-{{ 
                                                        $appointment->status == 'approved' ? 'success' : 
                                                        ($appointment->status == 'pending' ? 'warning' : 'danger') 
                                                    }}">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </p>
                                                <p><strong>Dibuat:</strong> {{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>

                                        <p><strong>Keluhan:</strong></p>
                                        <div class="border p-3 rounded">{{ $appointment->keluhan_singkat }}</div>

                                        @if($appointment->alasan_reject)
                                        <div class="mt-3">
                                            <p><strong>Alasan Penolakan:</strong></p>
                                            <div class="border p-3 rounded bg-light">{{ $appointment->alasan_reject }}</div>
                                        </div>
                                        @endif

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ============================================================ --}}
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $appointments->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Belum ada janji temu</h4>
            <p>Silahkan buat janji temu pertama Anda.</p>

            <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Buat Janji
            </a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection
