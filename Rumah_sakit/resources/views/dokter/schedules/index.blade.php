@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="page-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Jadwal Praktik Saya</h1>
                <p class="page-subtitle">Kelola jadwal praktik dan ketersediaan waktu Anda</p>
            </div>
            <div class="text-white">
                <p class="mb-0 fw-medium">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Total: {{ $schedules->count() }} Jadwal
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

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-primary);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-number text-primary">{{ $schedules->count() }}</div>
                <div class="stats-label">Total Jadwal</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-success);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number text-success">{{ $schedules->sum('durasi') / 60 }}</div>
                <div class="stats-label">Jam Praktik/Minggu</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-info);">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="stats-number text-info">{{ $schedules->pluck('hari')->unique()->count() }}</div>
                <div class="stats-label">Hari Praktik</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon" style="background: var(--gradient-warning);">
                    <i class="fas fa-stopwatch"></i>
                </div>
                <div class="stats-number text-warning">{{ $schedules->count() > 0 ? round($schedules->avg('durasi')) : 0 }} menit</div>
                <div class="stats-label">Rata-rata Durasi</div>
            </div>
        </div>
    </div>

    <!-- Action Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="text-primary mb-0">
                <i class="fas fa-list me-2"></i>Daftar Jadwal Praktik
            </h5>
        </div>
        <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            <i class="fas fa-plus me-2"></i>Tambah Jadwal
        </button>
    </div>

    <!-- Schedule Table -->
    <div class="content-card">
        <div class="card-body-custom">
            @if($schedules->count() > 0)
                <div class="table-container">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th width="20%">Hari</th>
                                <th width="20%">Jam Mulai</th>
                                <th width="20%">Jam Selesai</th>
                                <th width="15%">Durasi</th>
                                <th width="25%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="day-badge me-3">
                                            <i class="fas fa-calendar-day"></i>
                                        </div>
                                        <div>
                                            <strong class="text-primary">{{ $schedule->hari }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="time-badge bg-primary">
                                        <i class="fas fa-play me-1"></i>
                                        {{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="time-badge bg-success">
                                        <i class="fas fa-stop me-1"></i>
                                        {{ \Carbon\Carbon::parse($schedule->jam_mulai)->addMinutes($schedule->durasi)->format('H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="duration-badge">
                                        <i class="fas fa-hourglass-half me-1"></i>
                                        {{ $schedule->durasi }} menit
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-warning-custom btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editScheduleModal"
                                                data-id="{{ $schedule->id }}"
                                                data-hari="{{ $schedule->hari }}"
                                                data-jam-mulai="{{ $schedule->jam_mulai }}"
                                                data-durasi="{{ $schedule->durasi }}"
                                                title="Edit Jadwal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('dokter.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger-custom btn-sm" 
                                                    onclick="return confirm('Yakin ingin menghapus jadwal ini?')"
                                                    title="Hapus Jadwal">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <h5 class="empty-state-title">Belum ada jadwal praktik</h5>
                    <p class="empty-state-text">Mulai dengan menambahkan jadwal praktik pertama Anda.</p>
                    <div class="mt-3">
                        <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                            <i class="fas fa-plus me-2"></i>Tambah Jadwal Pertama
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-content-custom">
            <form action="{{ route('dokter.schedules.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title modal-title-custom">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Jadwal Praktik
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="mb-3">
                        <label for="hari" class="form-label-custom">Hari <span class="text-danger">*</span></label>
                        <select class="form-control-custom" id="hari" name="hari" required>
                            <option value="">Pilih Hari</option>
                            @foreach($days as $key => $value)
                                <option value="{{ $key }}" {{ old('hari') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label-custom">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control-custom" id="jam_mulai" name="jam_mulai" 
                               value="{{ old('jam_mulai') }}" step="1800" required>
                        <div class="form-help-text">
                            <i class="fas fa-info-circle me-1"></i>Pilih jam dengan interval 30 menit
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="durasi" class="form-label-custom">Durasi Sesi <span class="text-danger">*</span></label>
                        <select class="form-control-custom" id="durasi" name="durasi" required>
                            <option value="30" {{ old('durasi', '30') == '30' ? 'selected' : '' }}>30 menit</option>
                            <option value="45" {{ old('durasi') == '45' ? 'selected' : '' }}>45 menit</option>
                            <option value="60" {{ old('durasi') == '60' ? 'selected' : '' }}>60 menit</option>
                        </select>
                        <div class="form-help-text">
                            <i class="fas fa-clock me-1"></i>Durasi per sesi konsultasi
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save me-2"></i>Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-content-custom">
            <form id="editScheduleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title modal-title-custom">
                        <i class="fas fa-edit me-2 text-warning"></i>Edit Jadwal Praktik
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="mb-3">
                        <label for="edit_hari" class="form-label-custom">Hari <span class="text-danger">*</span></label>
                        <select class="form-control-custom" id="edit_hari" name="hari" required>
                            <option value="">Pilih Hari</option>
                            @foreach($days as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jam_mulai" class="form-label-custom">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control-custom" id="edit_jam_mulai" name="jam_mulai" 
                               step="1800" required>
                        <div class="form-help-text">
                            <i class="fas fa-info-circle me-1"></i>Pilih jam dengan interval 30 menit
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_durasi" class="form-label-custom">Durasi Sesi <span class="text-danger">*</span></label>
                        <select class="form-control-custom" id="edit_durasi" name="durasi" required>
                            <option value="30">30 menit</option>
                            <option value="45">45 menit</option>
                            <option value="60">60 menit</option>
                        </select>
                        <div class="form-help-text">
                            <i class="fas fa-clock me-1"></i>Durasi per sesi konsultasi
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning-custom">
                        <i class="fas fa-sync-alt me-2"></i>Update Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit Schedule Modal
    const editScheduleModal = document.getElementById('editScheduleModal');
    editScheduleModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const scheduleId = button.getAttribute('data-id');
        const hari = button.getAttribute('data-hari');
        const jamMulai = button.getAttribute('data-jam-mulai');
        const durasi = button.getAttribute('data-durasi');

        const form = document.getElementById('editScheduleForm');
        form.action = `/dokter/schedules/${scheduleId}`;

        document.getElementById('edit_hari').value = hari;
        document.getElementById('edit_jam_mulai').value = jamMulai;
        document.getElementById('edit_durasi').value = durasi;
    });

    // Force 30-minute intervals for time input
    const timeInputs = document.querySelectorAll('input[type="time"]');
    timeInputs.forEach(input => {
        input.addEventListener('change', function() {
            const time = this.value;
            if (time) {
                const [hours, minutes] = time.split(':');
                const roundedMinutes = Math.round(minutes / 30) * 30;
                this.value = `${hours}:${roundedMinutes.toString().padStart(2, '0')}`;
            }
        });
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
/* Custom Badges */
.day-badge {
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.time-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
}

.duration-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    font-weight: 600;
    font-size: 0.85rem;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

/* Warning Button Custom */
.btn-warning-custom {
    background: var(--gradient-warning);
    color: var(--white);
    border: none;
    padding: 0.5rem 1rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
}

.btn-warning-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    color: var(--white);
}

/* Day Schedule Cards */
.day-schedule-card {
    background: var(--glass-bg);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(0, 101, 193, 0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.day-schedule-card.has-schedule {
    border-left: 4px solid var(--success);
}

.day-schedule-card.no-schedule {
    border-left: 4px solid var(--light-text);
    opacity: 0.7;
}

.day-schedule-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 101, 193, 0.1);
}

.day-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.day-title {
    font-weight: 600;
    color: var(--primary-blue);
    margin: 0;
}

.day-count {
    background: var(--gradient-primary);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
}

.schedule-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.schedule-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.schedule-item:last-child {
    border-bottom: none;
}

.schedule-time {
    font-weight: 600;
    color: var(--dark-text);
    flex: 1;
}

.schedule-duration {
    color: var(--light-text);
    font-size: 0.8rem;
}

.no-schedule-text {
    text-align: center;
    padding: 1rem 0;
    color: var(--light-text);
}

/* Form Help Text */
.form-help-text {
    font-size: 0.8rem;
    color: var(--light-text);
    margin-top: 0.25rem;
    font-style: italic;
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .day-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .day-count {
        align-self: flex-start;
    }
    
    .schedule-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .stats-number {
        font-size: 1.5rem;
    }
    
    .stats-card {
        padding: 1rem;
    }
}
</style>
@endpush