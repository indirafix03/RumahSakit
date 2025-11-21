@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Jadwal Praktik Saya</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Durasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->hari }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->jam_mulai)->addMinutes($schedule->durasi)->format('H:i') }}</td>
                            <td>{{ $schedule->durasi }} menit</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editScheduleModal"
                                        data-id="{{ $schedule->id }}"
                                        data-hari="{{ $schedule->hari }}"
                                        data-jam-mulai="{{ $schedule->jam_mulai }}"
                                        data-durasi="{{ $schedule->durasi }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('dokter.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($schedules->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">Belum ada jadwal praktik</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dokter.schedules.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal Praktik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="hari" class="form-label">Hari</label>
                        <select class="form-control" id="hari" name="hari" required>
                            <option value="">Pilih Hari</option>
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" 
                               value="{{ old('jam_mulai') }}" step="1800" required>
                        <div class="form-text">Pilih jam dengan interval 30 menit</div>
                    </div>
                    <div class="mb-3">
                        <label for="durasi" class="form-label">Durasi</label>
                        <select class="form-control" id="durasi" name="durasi" required>
                            <option value="30" selected>30 menit</option>
                            <option value="45">45 menit</option>
                            <option value="60">60 menit</option>
                        </select>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editScheduleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jadwal Praktik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_hari" class="form-label">Hari</label>
                        <select class="form-control" id="edit_hari" name="hari" required>
                            <option value="">Pilih Hari</option>
                            @foreach($days as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" class="form-control" id="edit_jam_mulai" name="jam_mulai" 
                               step="1800" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_durasi" class="form-label">Durasi</label>
                        <select class="form-control" id="edit_durasi" name="durasi" required>
                            <option value="30">30 menit</option>
                            <option value="45">45 menit</option>
                            <option value="60">60 menit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
});
</script>
@endsection