@extends('layouts.app')

@section('title', 'Edit Janji Temu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Edit Janji Temu</h1>
                <a href="{{ route('pasien.appointments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('pasien.appointments.update', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dokter_id">Dokter</label>
                                    <select class="form-control" id="dokter_id" name="dokter_id" required>
                                        <option value="">Pilih Dokter...</option>
                                        @foreach($polis as $poli)
                                            <optgroup label="{{ $poli->nama_poli }}">
                                                @foreach($poli->doctors as $doctor)
                                                    <option value="{{ $doctor->id }}" 
                                                        {{ $appointment->dokter_id == $doctor->id ? 'selected' : '' }}>
                                                        Dr. {{ $doctor->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="schedule_id">Jadwal</label>
                                    <select class="form-control" id="schedule_id" name="schedule_id" required>
                                        <option value="">Pilih Jadwal...</option>
                                        <!-- Jadwal akan di-load via JavaScript dan PHP -->
                                        @isset($availableSchedules)
                                            @foreach($availableSchedules as $schedule)
                                                <option value="{{ $schedule['id'] }}" 
                                                    {{ $appointment->schedule_id == $schedule['id'] ? 'selected' : '' }}>
                                                    {{ $schedule['jam_mulai'] }} - {{ $schedule['jam_selesai'] }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_booking">Tanggal Booking</label>
                                    <input type="date" class="form-control" id="tanggal_booking" name="tanggal_booking" 
                                           value="{{ $appointment->tanggal_booking->format('Y-m-d') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keluhan_singkat">Keluhan Singkat</label>
                            <textarea class="form-control" id="keluhan_singkat" name="keluhan_singkat" 
                                      rows="4" placeholder="Jelaskan keluhan Anda secara singkat..." 
                                      maxlength="500" required>{{ $appointment->keluhan_singkat }}</textarea>
                            <small class="form-text text-muted">
                                <span id="charCount">{{ strlen($appointment->keluhan_singkat) }}</span>/500 karakter
                            </small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Janji Temu
                            </button>
                            <a href="{{ route('pasien.appointments.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Janji Temu</h6>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $appointment->status == 'pending' ? 'warning' : 'secondary' }}">
                            {{ $appointment->status }}
                        </span>
                    </p>
                    <p><strong>Dibuat:</strong> {{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Terakhir Diupdate:</strong> {{ $appointment->updated_at->format('d/m/Y H:i') }}</p>
                    
                    @if($appointment->status != 'pending')
                    <div class="alert alert-warning">
                        <small>
                            <i class="fas fa-exclamation-triangle"></i> 
                            Hanya janji temu dengan status "pending" yang dapat diedit.
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Janji Temu Saat Ini</h6>
                </div>
                <div class="card-body">
                    <p><strong>Dokter:</strong> Dr. {{ $appointment->dokter->name }}</p>
                    <p><strong>Tanggal:</strong> {{ $appointment->tanggal_booking->format('d/m/Y') }}</p>
                    <p><strong>Waktu:</strong> 
                        @if($appointment->schedule)
                            {{ $appointment->schedule->jam_mulai }} 
                            ({{ $appointment->schedule->durasi }} menit)
                        @else
                            Tidak tersedia
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Character counter
    $('#keluhan_singkat').on('input', function() {
        $('#charCount').text($(this).val().length);
    });

    // Load schedules when doctor or date changes
    function loadSchedules() {
        const dokterId = $('#dokter_id').val();
        const tanggal = $('#tanggal_booking').val();
        
        if (dokterId && tanggal) {
            $('#schedule_id').prop('disabled', true).html('<option value="">Memuat jadwal...</option>');
            
            // Gunakan URL langsung
            let urlTimeSlots = '/pasien/get-time-slots/' + dokterId + '/' + tanggal;
            
            console.log('Loading schedules from:', urlTimeSlots);
            
            $.get(urlTimeSlots)
                .done(function(data) {
                    console.log('Schedules data received:', data);
                    
                    $('#schedule_id').empty().append('<option value="">Pilih Jadwal...</option>');
                    if (data.length > 0) {
                        data.forEach(function(slot) {
                            $('#schedule_id').append(
                                `<option value="${slot.id}">${slot.jam_mulai} - ${slot.jam_selesai}</option>`
                            );
                        });
                        $('#schedule_id').prop('disabled', false);
                        
                        // Set selected schedule if exists (current appointment schedule)
                        const currentScheduleId = {{ $appointment->schedule_id }};
                        if (currentScheduleId) {
                            $('#schedule_id').val(currentScheduleId);
                        }
                    } else {
                        $('#schedule_id').append('<option value="">Tidak ada jadwal tersedia</option>');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('Error loading schedules:', error);
                    $('#schedule_id').empty().append('<option value="">Gagal memuat jadwal</option>');
                });
        } else {
            $('#schedule_id').empty().append('<option value="">Pilih dokter dan tanggal terlebih dahulu</option>');
        }
    }

    $('#dokter_id, #tanggal_booking').on('change', loadSchedules);

    // Initial load - jika dokter dan tanggal sudah dipilih
    const currentDokterId = $('#dokter_id').val();
    const currentTanggal = $('#tanggal_booking').val();
    
    if (currentDokterId && currentTanggal) {
        // Jika sudah ada jadwal dari PHP, biarkan seperti itu
        // Jika tidak, load via AJAX
        if ($('#schedule_id option').length <= 1) {
            loadSchedules();
        }
    }
});
</script>
@endsection