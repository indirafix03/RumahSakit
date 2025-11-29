@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="page-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Manajemen Janji Temu</h1>
                <p class="page-subtitle">Kelola semua janji temu pasien Anda</p>
            </div>
            <div class="text-white">
                <p class="mb-0 fw-medium">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Total: {{ $pendingAppointments->count() + $approvedAppointments->count() + $rejectedAppointments->count() + $completedAppointments->count() }} Janji
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    @if(session('success'))
        <div class="alert-custom alert-success-custom alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <strong>Sukses!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-custom alert-danger-custom alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-success);">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stats-number text-success">{{ $approvedAppointments->count() }}</div>
                <div class="stats-label">Disetujui</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-danger);">
                    <i class="fas fa-times"></i>
                </div>
                <div class="stats-number text-danger">{{ $rejectedAppointments->count() }}</div>
                <div class="stats-label">Ditolak</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-info);">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stats-number text-info">{{ $completedAppointments->count() }}</div>
                <div class="stats-label">Selesai</div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="content-card">
        <div class="card-header-custom">
            <ul class="nav nav-tabs-custom" id="appointmentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link-custom active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                        <i class="fas fa-clock me-2"></i>Pending
                        <span class="tab-badge bg-warning">{{ $pendingAppointments->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link-custom" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                        <i class="fas fa-check me-2"></i>Disetujui
                        <span class="tab-badge bg-success">{{ $approvedAppointments->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link-custom" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button">
                        <i class="fas fa-times me-2"></i>Ditolak
                        <span class="tab-badge bg-danger">{{ $rejectedAppointments->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link-custom" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                        <i class="fas fa-check-double me-2"></i>Selesai
                        <span class="tab-badge bg-info">{{ $completedAppointments->count() }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body-custom">
            <div class="tab-content" id="appointmentTabsContent">
                <!-- Pending Appointments -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    @if($pendingAppointments->count() > 0)
                        <div class="table-container">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th width="20%">Pasien</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="15%">Waktu</th>
                                        <th width="30%">Keluhan</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingAppointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-primary">{{ $appointment->pasien->name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->pasien->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $appointment->tanggal_booking?->format('d/m/Y') ?? 'T/A' }}</strong>
                                        </td>
                                        <td>
                                            @if($appointment->schedule)
                                                <span class="badge-custom badge-primary">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->schedule->jam_mulai)->format('H:i') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 250px;" 
                                                 data-bs-toggle="tooltip" 
                                                 title="{{ $appointment->keluhan_singkat ?? 'Tidak ada keluhan' }}">
                                                {{ Str::limit($appointment->keluhan_singkat ?? 'Tidak ada keluhan', 50) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-success-custom btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#approveModal"
                                                        data-id="{{ $appointment->id }}"
                                                        data-patient="{{ $appointment->pasien->name ?? 'Pasien' }}">
                                                    <i class="fas fa-check me-1"></i>Setujui
                                                </button>
                                                <button type="button" class="btn btn-danger-custom btn-sm"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal"
                                                        data-id="{{ $appointment->id }}"
                                                        data-patient="{{ $appointment->pasien->name ?? 'Pasien' }}">
                                                    <i class="fas fa-times me-1"></i>Tolak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5 class="empty-state-title">Tidak ada janji temu pending</h5>
                            <p class="empty-state-text">Semua janji temu sudah diproses atau belum ada yang masuk.</p>
                        </div>
                    @endif
                </div>

                <!-- Approved Appointments -->
                <div class="tab-pane fade" id="approved" role="tabpanel">
                    @if($approvedAppointments->count() > 0)
                        <div class="table-container">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th width="25%">Pasien</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="15%">Waktu</th>
                                        <th width="35%">Keluhan</th>
                                        <th width="10%" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedAppointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-success">{{ $appointment->pasien->name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->pasien->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $appointment->tanggal_booking?->format('d/m/Y') ?? 'T/A' }}</strong>
                                        </td>
                                        <td>
                                            @if($appointment->schedule)
                                                <span class="badge-custom badge-success">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->schedule->jam_mulai)->format('H:i') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;">
                                                {{ Str::limit($appointment->keluhan_singkat ?? 'Tidak ada keluhan', 60) }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-custom badge-success">
                                                <i class="fas fa-check me-1"></i>Disetujui
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <h5 class="empty-state-title">Tidak ada janji temu yang disetujui</h5>
                            <p class="empty-state-text">Belum ada janji temu yang disetujui.</p>
                        </div>
                    @endif
                </div>

                <!-- Rejected Appointments -->
                <div class="tab-pane fade" id="rejected" role="tabpanel">
                    @if($rejectedAppointments->count() > 0)
                        <div class="table-container">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th width="25%">Pasien</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="40%">Alasan Penolakan</th>
                                        <th width="20%" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejectedAppointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-danger rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-danger">{{ $appointment->pasien->name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->pasien->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $appointment->tanggal_booking?->format('d/m/Y') ?? 'T/A' }}</strong>
                                        </td>
                                        <td>
                                            @if($appointment->alasan_reject) 
                                                <div class="text-truncate" style="max-width: 400px;" 
                                                     data-bs-toggle="tooltip" 
                                                     title="{{ $appointment->alasan_reject }}">
                                                    {{ $appointment->alasan_reject }}
                                                </div>
                                            @else
                                                <span class="text-muted">Tidak ada alasan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-custom badge-danger">
                                                <i class="fas fa-times me-1"></i>Ditolak
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-times"></i>
                            </div>
                            <h5 class="empty-state-title">Tidak ada janji temu yang ditolak</h5>
                            <p class="empty-state-text">Belum ada janji temu yang ditolak.</p>
                        </div>
                    @endif
                </div>

                <!-- Completed Appointments -->
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    @if($completedAppointments->count() > 0)
                        <div class="table-container">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th width="25%">Pasien</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="40%">Diagnosis</th>
                                        <th width="20%" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedAppointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-info">{{ $appointment->pasien->name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->pasien->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $appointment->tanggal_booking?->format('d/m/Y') ?? 'T/A' }}</strong>
                                        </td>
                                        <td>
                                            @if($appointment->medicalRecord)
                                                <div class="text-truncate" style="max-width: 400px;" 
                                                     data-bs-toggle="tooltip" 
                                                     title="{{ $appointment->medicalRecord->diagnosis }}">
                                                    {{ Str::limit($appointment->medicalRecord->diagnosis, 80) }}
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-custom badge-info">
                                                <i class="fas fa-check-double me-1"></i>Selesai
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <h5 class="empty-state-title">Tidak ada janji temu yang selesai</h5>
                            <p class="empty-state-text">Belum ada janji temu yang selesai.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-content-custom">
            <form id="approveForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title modal-title-custom">
                        <i class="fas fa-check-circle me-2 text-success"></i>Setujui Janji Temu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                        <h5 class="mb-3">Konfirmasi Persetujuan</h5>
                        <p class="text-muted" id="approvePatientInfo">Apakah Anda yakin ingin menyetujui janji temu ini?</p>
                    </div>
                    <input type="hidden" name="status" value="approved">
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success-custom">Setujui Janji Temu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-content-custom">
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title modal-title-custom">
                        <i class="fas fa-times-circle me-2 text-danger"></i>Tolak Janji Temu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="text-center mb-4">
                        <i class="fas fa-times-circle text-danger mb-3" style="font-size: 3rem;"></i>
                        <h5 class="mb-3">Konfirmasi Penolakan</h5>
                        <p class="text-muted" id="rejectPatientInfo">Silakan berikan alasan penolakan untuk janji temu ini.</p>
                    </div>
                    <div class="mb-3">
                        <label for="alasan_reject" class="form-label-custom">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control-custom" id="alasan_reject" name="alasan_reject" rows="4" 
                                  placeholder="Berikan alasan penolakan yang jelas dan informatif..." required></textarea>
                    </div>
                    <input type="hidden" name="status" value="rejected">
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger-custom">Tolak Janji Temu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Approve Modal
    const approveModal = document.getElementById('approveModal');
    approveModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const appointmentId = button.getAttribute('data-id');
        const patientName = button.getAttribute('data-patient');
        const form = document.getElementById('approveForm');
        const patientInfo = document.getElementById('approvePatientInfo');
        
        form.action = `/dokter/appointments/${appointmentId}/status`;
        patientInfo.textContent = `Apakah Anda yakin ingin menyetujui janji temu dengan ${patientName}?`;
    });

    // Reject Modal
    const rejectModal = document.getElementById('rejectModal');
    rejectModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const appointmentId = button.getAttribute('data-id');
        const patientName = button.getAttribute('data-patient');
        const form = document.getElementById('rejectForm');
        const patientInfo = document.getElementById('rejectPatientInfo');
        
        form.action = `/dokter/appointments/${appointmentId}/status`;
        patientInfo.textContent = `Silakan berikan alasan penolakan untuk janji temu dengan ${patientName}.`;
        
        // Reset textarea
        document.getElementById('alasan_reject').value = '';
    });
});
</script>

<style>
/* Custom Tabs */
.nav-tabs-custom {
    border-bottom: 1px solid rgba(0, 101, 193, 0.1);
    display: flex;
    gap: 0.5rem;
}

.nav-link-custom {
    border: none;
    background: transparent;
    color: var(--light-text);
    padding: 1rem 1.5rem;
    border-radius: 10px 10px 0 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link-custom:hover {
    color: var(--primary-blue);
    background: rgba(0, 101, 193, 0.05);
}

.nav-link-custom.active {
    color: var(--primary-blue);
    background: var(--white);
    border-bottom: 3px solid var(--primary-blue);
}

.tab-badge {
    margin-left: 0.5rem;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

/* Avatar */
.avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Danger Button Custom */
.btn-danger-custom {
    background: var(--gradient-danger);
    color: var(--white);
    border: none;
    padding: 0.5rem 1rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
}

.btn-danger-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    color: var(--white);
}

/* Secondary Button Custom */
.btn-secondary-custom {
    background: rgba(255, 255, 255, 0.9);
    color: var(--primary-blue);
    border: 1px solid rgba(0, 101, 193, 0.2);
    padding: 0.8rem 1.5rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-secondary-custom:hover {
    background: rgba(255, 255, 255, 1);
    border-color: var(--secondary-blue);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 101, 193, 0.1);
    color: var(--primary-blue);
}

/* Alert Custom */
.alert-custom {
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border: none;
    backdrop-filter: blur(10px);
}

.alert-success-custom {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
    color: var(--success);
    border-left: 4px solid var(--success);
}

.alert-danger-custom {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
    color: var(--danger);
    border-left: 4px solid var(--danger);
}

/* Responsive adjustments for tabs */
@media (max-width: 768px) {
    .nav-tabs-custom {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .nav-link-custom {
        border-radius: 8px;
        margin-bottom: 0.25rem;
    }
    
    .table-container {
        overflow-x: auto;
    }
}
</style>
@endpush