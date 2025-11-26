@extends('layouts.app')

@section('title', 'Jadwal Dokter')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Jadwal Dokter Tersedia</h1>
                <div>
                    <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Buat Janji Temu
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('pasien.schedules.index') }}" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="poli"><strong>Filter Berdasarkan Poli</strong></label>
                            <select name="poli" id="poli" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Poli</option>
                                @foreach($polis as $poli)
                                    <option value="{{ $poli->id }}" {{ $poliFilter == $poli->id ? 'selected' : '' }}>
                                        {{ $poli->nama_poli }} ({{ $poli->doctors_count }} dokter)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="day"><strong>Filter Berdasarkan Hari</strong></label>
                            <select name="day" id="day" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Hari</option>
                                @foreach($availableDays as $day)
                                    <option value="{{ $day }}" {{ $dayFilter == $day ? 'selected' : '' }}>
                                        {{ ucfirst($day) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="view"><strong>Tampilan</strong></label>
                            <select name="view" id="view" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="grid" {{ $viewType == 'grid' ? 'selected' : '' }}>Grid View</option>
                                <option value="list" {{ $viewType == 'list' ? 'selected' : '' }}>List View</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                @if($poliFilter || $dayFilter)
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-2">Filter aktif:</span>
                            @if($poliFilter)
                                @php
                                    $selectedPoli = $polis->where('id', $poliFilter)->first();
                                @endphp
                                <span class="badge bg-primary me-2">
                                    Poli: {{ $selectedPoli->nama_poli ?? '' }}
                                    <a href="?{{ http_build_query(array_merge(request()->except('poli'), ['view' => $viewType])) }}" class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if($dayFilter)
                                <span class="badge bg-info me-2">
                                    Hari: {{ ucfirst($dayFilter) }}
                                    <a href="?{{ http_build_query(array_merge(request()->except('day'), ['view' => $viewType])) }}" class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            <a href="{{ route('pasien.schedules.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                <i class="fas fa-times"></i> Hapus Semua Filter
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    @if($schedules->count() > 0)
        @if($viewType == 'grid')
            <!-- Grid View -->
            <div class="row">
                @foreach($groupedSchedules as $day => $daySchedules)
                <div class="col-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-day me-2"></i>
                                {{ ucfirst($day) }}
                                <span class="badge bg-light text-primary ms-2">{{ $daySchedules->count() }} jadwal</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($daySchedules as $schedule)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title text-primary mb-1">
                                                        Dr. {{ $schedule->doctor->name }}
                                                    </h6>
                                                    <p class="card-text small text-muted mb-1">
                                                        <i class="fas fa-stethoscope me-1"></i>
                                                        {{ $schedule->doctor->poli->nama_poli ?? 'N/A' }}
                                                    </p>
                                                    <p class="card-text small text-muted mb-2">
                                                        <i class="fas fa-user-md me-1"></i>
                                                        {{ $schedule->doctor->spesialisasi ?? 'Umum' }}
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->jam_selesai)->format('H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-stopwatch me-1"></i>
                                                    {{ $schedule->durasi }} menit
                                                </small>
                                                <div>
                                                    <button class="btn btn-sm btn-outline-primary me-1" 
                                                            onclick="showDoctorDetail({{ $schedule->doctor->id }})"
                                                            data-bs-toggle="tooltip" title="Lihat Detail Dokter">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                    <a href="{{ route('pasien.appointments.create') }}?dokter_id={{ $schedule->doctor->id }}&schedule_id={{ $schedule->id }}" 
                                                       class="btn btn-sm btn-success"
                                                       data-bs-toggle="tooltip" title="Buat Janji dengan Dokter Ini">
                                                        <i class="fas fa-calendar-check"></i> Buat Janji
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- List View -->
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Dokter</th>
                                    <th>Poli</th>
                                    <th>Spesialisasi</th>
                                    <th>Durasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ ucfirst($schedule->hari) }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->jam_selesai)->format('H:i') }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <strong>Dr. {{ $schedule->doctor->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $schedule->doctor->bio ? Str::limit($schedule->doctor->bio, 50) : '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $schedule->doctor->poli->nama_poli ?? 'N/A' }}</td>
                                    <td>{{ $schedule->doctor->spesialisasi ?? 'Umum' }}</td>
                                    <td>{{ $schedule->durasi }} menit</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-info" 
                                                    onclick="showDoctorDetail({{ $schedule->doctor->id }})"
                                                    data-bs-toggle="tooltip" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('pasien.appointments.create') }}?dokter_id={{ $schedule->doctor->id }}&schedule_id={{ $schedule->id }}" 
                                               class="btn btn-success"
                                               data-bs-toggle="tooltip" title="Buat Janji">
                                                <i class="fas fa-calendar-plus"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-user-md fa-2x mb-2"></i>
                        <h4>{{ $schedules->unique('dokter_id')->count() }}</h4>
                        <p class="mb-0">Dokter Tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                        <h4>{{ $schedules->count() }}</h4>
                        <p class="mb-0">Total Jadwal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-clinic-medical fa-2x mb-2"></i>
                        <h4>{{ $schedules->unique(function($item) { return $item->doctor->poli_id; })->count() }}</h4>
                        <p class="mb-0">Poli Tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <h4>{{ $availableDays->count() }}</h4>
                        <p class="mb-0">Hari Praktik</p>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Tidak Ada Jadwal Tersedia</h4>
            <p class="text-muted">
                @if($poliFilter || $dayFilter)
                    Tidak ditemukan jadwal dengan filter yang dipilih. Coba ubah filter atau 
                    <a href="{{ route('pasien.schedules.index') }}">lihat semua jadwal</a>.
                @else
                    Belum ada jadwal dokter yang tersedia saat ini.
                @endif
            </p>
            <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-calendar-plus me-1"></i> Buat Janji Temu
            </a>
        </div>
    @endif
</div>

<!-- Modal for Doctor Details -->
<div class="modal fade" id="doctorDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Dokter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="doctorDetailContent">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn btn-primary" id="makeAppointmentBtn">
                    <i class="fas fa-calendar-check me-1"></i> Buat Janji
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showDoctorDetail(doctorId) {
    // Show loading
    $('#doctorDetailContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Memuat data dokter...</p>
        </div>
    `);
    
    $('#doctorDetailModal').modal('show');

    // Fetch doctor details
    fetch(`/pasien/doctors/${doctorId}/schedule`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                $('#doctorDetailContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${data.error}
                    </div>
                `);
                return;
            }

            const doctor = data.doctor;
            const schedules = data.schedules;

            let scheduleHTML = '';
            if (schedules.length > 0) {
                scheduleHTML = `
                    <h6 class="mt-4">Jadwal Praktik:</h6>
                    <div class="row">
                        ${schedules.map(schedule => `
                            <div class="col-md-6 mb-2">
                                <div class="border p-2 rounded">
                                    <strong>${schedule.hari}</strong><br>
                                    <small class="text-muted">${schedule.jam_mulai} - ${schedule.jam_selesai}</small>
                                    <br>
                                    <small class="text-muted">Durasi: ${schedule.durasi} menit</small>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
            } else {
                scheduleHTML = '<p class="text-muted">Belum ada jadwal tersedia.</p>';
            }

            $('#doctorDetailContent').html(`
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="text-primary">Dr. ${doctor.name}</h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-stethoscope me-2"></i>${doctor.poli}
                        </p>
                        <p class="text-muted mb-3">
                            <i class="fas fa-user-md me-2"></i>${doctor.spesialisasi || 'Umum'}
                        </p>
                        ${doctor.bio ? `<p>${doctor.bio}</p>` : '<p class="text-muted">Tidak ada deskripsi tambahan.</p>'}
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bg-light rounded p-3">
                            <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                            <p class="small text-muted mb-0">Dokter ${doctor.poli}</p>
                        </div>
                    </div>
                </div>
                ${scheduleHTML}
            `);

            // Update appointment button
            $('#makeAppointmentBtn').attr('href', `/pasien/appointments/create?dokter_id=${doctor.id}`);
        })
        .catch(error => {
            $('#doctorDetailContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Terjadi kesalahan saat memuat data dokter.
                </div>
            `);
        });
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush