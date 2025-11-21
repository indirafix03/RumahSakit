@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Manajemen Janji Temu</h1>
            <p class="text-muted">Kelola semua janji temu pasien</p>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-primary" onclick="loadStatistics()">
                <i class="fas fa-chart-bar me-2"></i>Lihat Statistik
            </button>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4" id="statsContainer" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik Janji Temu</h5>
                </div>
                <div class="card-body">
                    <div class="row" id="statsContent">
                        <!-- Stats will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.appointments.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $key => $value)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="poli_id" class="form-label">Poli</label>
                        <select class="form-select" id="poli_id" name="poli_id" onchange="this.form.submit()">
                            <option value="">Semua Poli</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id }}" {{ request('poli_id') == $poli->id ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" 
                               value="{{ request('tanggal') }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-refresh me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Appointments Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Daftar Janji Temu
                <span class="badge bg-primary">{{ $appointments->total() }}</span>
            </h5>
            <div class="text-muted small">
                Menampilkan {{ $appointments->firstItem() ?? 0 }}-{{ $appointments->lastItem() ?? 0 }} dari {{ $appointments->total() }} janji temu
            </div>
        </div>
        <div class="card-body p-0">
            @if($appointments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada janji temu</h5>
                    <p class="text-muted">Tidak ada janji temu yang sesuai dengan filter yang dipilih.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Pasien</th>
                                <th width="15%">Dokter & Poli</th>
                                <th width="15%">Tanggal & Waktu</th>
                                <th width="20%">Keluhan</th>
                                <th width="10%">Status</th>
                                <th width="20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $loop->iteration + ($appointments->currentPage() - 1) * $appointments->perPage() }}</td>
                                <td>
                                    <strong>{{ $appointment->pasien->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $appointment->pasien->email }}</small>
                                </td>
                                <td>
                                    <strong>Dr. {{ $appointment->dokter->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $appointment->dokter->poli->nama_poli ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $appointment->tanggal_booking->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        @if($appointment->schedule)
                                            {{ \Carbon\Carbon::parse($appointment->schedule->jam_mulai)->format('H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                          title="{{ $appointment->keluhan_singkat }}">
                                        {{ Str::limit($appointment->keluhan_singkat, 50) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'selesai' => 'info'
                                        ];
                                        $statusIcons = [
                                            'pending' => 'clock',
                                            'approved' => 'check',
                                            'rejected' => 'times',
                                            'selesai' => 'check-double'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                        <i class="fas fa-{{ $statusIcons[$appointment->status] ?? 'circle' }} me-1"></i>
                                        {{ $statuses[$appointment->status] ?? $appointment->status }}
                                    </span>
                                    @if($appointment->alasan_reject)
                                        <br>
                                        <small class="text-muted" title="{{ $appointment->alasan_reject }}">
                                            {{ Str::limit($appointment->alasan_reject, 30) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('admin.appointments.show', $appointment) }}" 
                                           class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Action Buttons for Pending Appointments -->
                                        @if($appointment->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" data-bs-target="#approveModal"
                                                    data-appointment-id="{{ $appointment->id }}"
                                                    title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal"
                                                    data-appointment-id="{{ $appointment->id }}"
                                                    title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif

                                        <!-- Delete Button -->
                                        @if(!$appointment->medicalRecord)
                                            <form action="{{ route('admin.appointments.destroy', $appointment) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Yakin ingin menghapus janji temu ini?')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled
                                                    title="Tidak dapat dihapus (sudah ada rekam medis)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($appointments->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $appointments->firstItem() }}-{{ $appointments->lastItem() }} dari {{ $appointments->total() }} janji temu
                        </div>
                        <div>
                            {{ $appointments->links() }}
                        </div>
                    </div>
                </div>
                @endif
            @endif
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
                        <label for="alasan_reject" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan_reject" name="alasan_reject" rows="3" 
                                  placeholder="Berikan alasan penolakan..." required></textarea>
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
@endsection

@push('scripts')
<script>
// Load statistics
function loadStatistics() {
    fetch('{{ route("admin.appointments.statistics") }}')
        .then(response => response.json())
        .then(data => {
            const statsContent = document.getElementById('statsContent');
            const statsContainer = document.getElementById('statsContainer');
            
            statsContent.innerHTML = `
                <div class="col-md-2">
                    <div class="text-center p-3 border rounded">
                        <h3 class="text-primary">${data.stats.total}</h3>
                        <p class="mb-0 text-muted">Total</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center p-3 border rounded">
                        <h3 class="text-warning">${data.stats.pending}</h3>
                        <p class="mb-0 text-muted">Pending</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center p-3 border rounded">
                        <h3 class="text-success">${data.stats.approved}</h3>
                        <p class="mb-0 text-muted">Disetujui</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center p-3 border rounded">
                        <h3 class="text-danger">${data.stats.rejected}</h3>
                        <p class="mb-0 text-muted">Ditolak</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center p-3 border rounded">
                        <h3 class="text-info">${data.stats.completed}</h3>
                        <p class="mb-0 text-muted">Selesai</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center p-3 border rounded">
                        <h3 class="text-primary">${data.stats.today}</h3>
                        <p class="mb-0 text-muted">Hari Ini</p>
                    </div>
                </div>
                ${data.poliStats.length > 0 ? `
                <div class="col-12 mt-3">
                    <h6 class="mb-3">Statistik per Poli:</h6>
                    <div class="row">
                        ${data.poliStats.map(poli => `
                            <div class="col-md-3 mb-2">
                                <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                    <span class="text-muted">${poli.nama_poli}</span>
                                    <span class="badge bg-primary">${poli.total}</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : ''}
            `;
            
            statsContainer.style.display = 'block';
            statsContainer.scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            alert('Gagal memuat statistik');
        });
}

// Modal handlers
document.addEventListener('DOMContentLoaded', function() {
    // Approve Modal
    const approveModal = document.getElementById('approveModal');
    approveModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const appointmentId = button.getAttribute('data-appointment-id');
        const form = document.getElementById('approveForm');
        form.action = `/admin/appointments/${appointmentId}/status`;
    });

    // Reject Modal
    const rejectModal = document.getElementById('rejectModal');
    rejectModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const appointmentId = button.getAttribute('data-appointment-id');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/appointments/${appointmentId}/status`;
        
        // Reset textarea
        document.getElementById('alasan_reject').value = '';
    });
});
</script>
@endpush