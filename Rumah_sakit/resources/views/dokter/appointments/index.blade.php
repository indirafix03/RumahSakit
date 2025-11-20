@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Janji Temu</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <ul class="nav nav-tabs" id="appointmentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                Pending <span class="badge bg-warning">{{ $pendingAppointments->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                Disetujui <span class="badge bg-success">{{ $approvedAppointments->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button">
                Ditolak <span class="badge bg-danger">{{ $rejectedAppointments->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                Selesai <span class="badge bg-info">{{ $completedAppointments->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="appointmentTabsContent">
        <!-- Pending Appointments -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="card mt-3">
                <div class="card-body">
                    @if($pendingAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Keluhan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->pasien->name }}</td>
                                    <td>{{ $appointment->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->jam }}</td>
                                    <td>{{ Str::limit($appointment->keluhan, 50) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal"
                                                data-id="{{ $appointment->id }}">
                                            Setujui
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal"
                                                data-id="{{ $appointment->id }}">
                                            Tolak
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">Tidak ada janji temu pending</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Approved Appointments -->
        <div class="tab-pane fade" id="approved" role="tabpanel">
            <div class="card mt-3">
                <div class="card-body">
                    @if($approvedAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Keluhan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($approvedAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->pasien->name }}</td>
                                    <td>{{ $appointment->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->jam }}</td>
                                    <td>{{ Str::limit($appointment->keluhan, 50) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">Tidak ada janji temu yang disetujui</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rejected Appointments -->
        <div class="tab-pane fade" id="rejected" role="tabpanel">
            <div class="card mt-3">
                <div class="card-body">
                    @if($rejectedAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Tanggal</th>
                                    <th>Alasan Penolakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rejectedAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->pasien->name }}</td>
                                    <td>{{ $appointment->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->alasan_penolakan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">Tidak ada janji temu yang ditolak</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Completed Appointments -->
        <div class="tab-pane fade" id="completed" role="tabpanel">
            <div class="card mt-3">
                <div class="card-body">
                    @if($completedAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Tanggal</th>
                                    <th>Diagnosis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->pasien->name }}</td>
                                    <td>{{ $appointment->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->medicalRecord ? Str::limit($appointment->medicalRecord->diagnosis, 50) : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">Tidak ada janji temu yang selesai</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Janji Temu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui janji temu ini?</p>
                    <input type="hidden" name="status" value="approved">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Janji Temu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="status" value="rejected">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Approve Modal
    const approveModal = document.getElementById('approveModal');
    approveModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const appointmentId = button.getAttribute('data-id');
        const form = document.getElementById('approveForm');
        form.action = `/dokter/appointments/${appointmentId}/status`;
    });

    // Reject Modal
    const rejectModal = document.getElementById('rejectModal');
    rejectModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const appointmentId = button.getAttribute('data-id');
        const form = document.getElementById('rejectForm');
        form.action = `/dokter/appointments/${appointmentId}/status`;
    });
});
</script>
@endsection