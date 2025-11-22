@extends('layouts.app')

@section('title', 'Buat Janji Temu Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Buat Janji Temu Baru</h1>
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

                    <form action="{{ route('pasien.appointments.store') }}" method="POST">
                        @csrf
                        <!-- Step 1: Pilih Poli -->
                        <div class="form-step step-1 active">
                            <h5 class="mb-3">1. Pilih Poli</h5>
                            <div class="row">
                                @foreach($polis as $poli)
                                <div class="col-md-4 mb-3">
                                    <div class="card poli-card" data-poli-id="{{ $poli->id }}">
                                        <div class="card-body text-center">
                                            @if($poli->ikon)
                                                <i class="{{ $poli->ikon }} fa-2x text-primary mb-2"></i>
                                            @else
                                                <i class="fas fa-stethoscope fa-2x text-primary mb-2"></i>
                                            @endif
                                            <h6 class="card-title">{{ $poli->nama_poli }}</h6>
                                            <p class="card-text small text-muted">{{ Str::limit($poli->deskripsi, 80) }}</p>
                                            <small class="text-muted">
                                                {{ $poli->doctors->count() }} dokter tersedia
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="poli_id" id="poli_id">
                            <button type="button" class="btn btn-primary next-step" disabled>Lanjutkan</button>
                        </div>

                        <!-- Step 2: Pilih Dokter -->
                        <div class="form-step step-2 d-none">
                            <h5 class="mb-3">2. Pilih Dokter</h5>
                            <div id="doctorsList" class="row">
                                <!-- Doctors will be loaded here via AJAX -->
                            </div>
                            <input type="hidden" name="dokter_id" id="dokter_id">
                            <div class="mt-3">
                                <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                                <button type="button" class="btn btn-primary next-step" disabled>Lanjutkan</button>
                            </div>
                        </div>

                        <!-- Step 3: Pilih Tanggal & Waktu -->
                        <div class="form-step step-3 d-none">
                            <h5 class="mb-3">3. Pilih Tanggal & Waktu</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_booking">Tanggal Booking</label>
                                        <input type="date" class="form-control" id="tanggal_booking" name="tanggal_booking" 
                                            min="{{ date('Y-m-d') }}" required> <!-- Bisa pilih hari ini -->
                                        <small class="form-text text-muted">Pilih tanggal kunjungan (bisa hari ini untuk testing)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="schedule_id">Pilih Waktu</label>
                                        <select class="form-control" id="schedule_id" name="schedule_id" required disabled>
                                            <option value="">Pilih waktu...</option>
                                        </select>
                                        <small class="form-text text-muted">Pilih jam praktik dokter</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                                <button type="button" class="btn btn-primary next-step" disabled>Lanjutkan</button>
                            </div>
                        </div>

                        <!-- Step 4: Isi Keluhan -->
                        <div class="form-step step-4 d-none">
                            <h5 class="mb-3">4. Isi Keluhan</h5>
                            <div class="form-group">
                                <label for="keluhan_singkat">Keluhan Singkat</label>
                                <textarea class="form-control" id="keluhan_singkat" name="keluhan_singkat" 
                                          rows="4" placeholder="Jelaskan keluhan Anda secara singkat..." 
                                          maxlength="500" required></textarea>
                                <small class="form-text text-muted">
                                    <span id="charCount">0</span>/500 karakter
                                </small>
                            </div>
                            
                            <!-- Summary -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Ringkasan Janji Temu</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Poli:</strong> <span id="summaryPoli">-</span></p>
                                    <p><strong>Dokter:</strong> <span id="summaryDokter">-</span></p>
                                    <p><strong>Tanggal:</strong> <span id="summaryTanggal">-</span></p>
                                    <p><strong>Waktu:</strong> <span id="summaryWaktu">-</span></p>
                                    <p><strong>Keluhan:</strong> <span id="summaryKeluhan">-</span></p>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-calendar-check"></i> Buat Janji Temu
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0">Informasi</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Petunjuk:</h6>
                        <ol class="pl-3 mb-0">
                            <li>Pilih poli yang sesuai dengan keluhan Anda</li>
                            <li>Pilih dokter yang tersedia</li>
                            <li>Tentukan tanggal dan waktu kunjungan</li>
                            <li>Jelaskan keluhan secara singkat</li>
                        </ol>
                    </div>
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Perhatian:</h6>
                        <ul class="pl-3 mb-0">
                            <li>Janji temu harus dibuat minimal 1 hari sebelumnya</li>
                            <li>Status janji temu akan diproses oleh admin/dokter</li>
                            <li>Anda dapat membatalkan janji temu yang masih pending</li>
                            <li>Maksimal 5 pasien per slot waktu</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentStep = 1;
    let selectedPoli = null;
    let selectedDokter = null;
    let selectedSchedule = null;

    // Character counter
    $('#keluhan_singkat').on('input', function() {
        $('#charCount').text($(this).val().length);
        $('#summaryKeluhan').text($(this).val() || '-');
    });

    // Next / Previous step
    $('.next-step').on('click', function() {
        if (!validateStep(currentStep)) return alert("Lengkapi langkah ini dulu!");
        $('.step-' + currentStep).addClass('d-none');
        currentStep++;
        $('.step-' + currentStep).removeClass('d-none');
    });

    $('.prev-step').on('click', function() {
        $('.step-' + currentStep).addClass('d-none');
        currentStep--;
        $('.step-' + currentStep).removeClass('d-none');
    });

    // PILIH POLI
    $(document).on('click', '.poli-card', function() {
        $('.poli-card').removeClass('border-primary');
        $(this).addClass('border-primary');

        selectedPoli = $(this).data('poli-id');
        $('#poli_id').val(selectedPoli);

        $('.step-1 .next-step').prop('disabled', false);
        $('#summaryPoli').text($(this).find('.card-title').text());

        loadDoctors(selectedPoli);
    });

    // GET DOCTORS
    function loadDoctors(poliId) {
        $('#doctorsList').html(`<div class="text-center p-3">
            <div class="spinner-border text-primary"></div>
            <p>Memuat dokter...</p></div>`);

        let urlGetDoctors = "{{ route('pasien.get-doctors', ':id') }}".replace(':id', poliId);

        $.get(urlGetDoctors)
            .done(data => {
                $('#doctorsList').empty();

                if (data.length === 0) {
                    return $('#doctorsList').html(
                        `<p class="text-muted text-center">Tidak ada dokter.</p>`
                    );
                }

                data.forEach(d => {
                    $('#doctorsList').append(`
                        <div class="col-md-6 mb-3">
                            <div class="card doctor-card" data-dokter-id="${d.id}" data-dokter-name="${d.name}">
                                <div class="card-body">
                                    <h6 class="card-title">Dr. ${d.name}</h6>
                                    <p class="small text-muted">${d.spesialisasi ?? '-'}</p>
                                    <p class="small"><i class="fas fa-clock"></i> ${d.schedules.length} jadwal</p>
                                </div>
                            </div>
                        </div>
                    `);
                });
            })
            .fail(() => alert("Gagal memuat dokter"));
    }

    // PILIH DOKTER
    $(document).on('click', '.doctor-card', function() {
        $('.doctor-card').removeClass('border-primary');
        $(this).addClass('border-primary');

        selectedDokter = $(this).data('dokter-id');
        $('#dokter_id').val(selectedDokter);
        $('.step-2 .next-step').prop('disabled', false);

        $('#summaryDokter').text('Dr. ' + $(this).data('dokter-name'));
    });

    // PILIH TANGGAL
    $('#tanggal_booking').on('change', function() {
        const date = $(this).val();
        $('#summaryTanggal').text(new Date(date).toLocaleDateString('id-ID'));
        if (date && selectedDokter) loadTimeSlots(selectedDokter, date);
    });

    // GET TIME SLOTS
    function loadTimeSlots(dokterId, date) {
        $('#schedule_id').prop('disabled', true).html('<option>Memuat...</option>');

        let urlTimeSlots = "{{ route('pasien.get-time-slots', [':dokterId', ':tanggal']) }}"
            .replace(':dokterId', dokterId)
            .replace(':tanggal', date);

        $.get(urlTimeSlots)
            .done(data => {
                $('#schedule_id').html('<option value="">Pilih waktu</option>');
                data.forEach(slot => {
                    $('#schedule_id').append(
                        `<option value="${slot.id}">${slot.jam_mulai} - ${slot.jam_selesai}</option>`
                    );
                });
                $('#schedule_id').prop('disabled', false);
            })
            .fail(() => alert("Gagal memuat jadwal"));
    }

    $('#schedule_id').on('change', function() {
        selectedSchedule = $(this).val();
        $('.step-3 .next-step').prop('disabled', !selectedSchedule);
        $('#summaryWaktu').text($(this).find('option:selected').text());
    });

    function validateStep(step) {
        return {
            1: selectedPoli !== null,
            2: selectedDokter !== null,
            3: $('#tanggal_booking').val() && selectedSchedule,
            4: $('#keluhan_singkat').val().trim() !== ""
        }[step];
    }
});
</script>
@endpush
