@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Edit Jadwal Praktik</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dokter.schedules.update', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="hari" class="form-label"><strong>Hari</strong> <span class="text-danger">*</span></label>
                            <select class="form-select @error('hari') is-invalid @enderror" id="hari" name="hari" required>
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin" {{ old('hari', $schedule->hari) === 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ old('hari', $schedule->hari) === 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ old('hari', $schedule->hari) === 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ old('hari', $schedule->hari) === 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ old('hari', $schedule->hari) === 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ old('hari', $schedule->hari) === 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                <option value="Minggu" {{ old('hari', $schedule->hari) === 'Minggu' ? 'selected' : '' }}>Minggu</option>
                            </select>
                            @error('hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jam_mulai" class="form-label"><strong>Jam Mulai</strong> <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" 
                                   id="jam_mulai" name="jam_mulai" 
                                   value="{{ old('jam_mulai', $schedule->jam_mulai) }}" required>
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="durasi" class="form-label"><strong>Durasi Konsultasi (menit)</strong> <span class="text-danger">*</span></label>
                            <select class="form-select @error('durasi') is-invalid @enderror" id="durasi" name="durasi" required>
                                <option value="">-- Pilih Durasi --</option>
                                <option value="30" {{ old('durasi', $schedule->durasi) == 30 ? 'selected' : '' }}>30 menit</option>
                                <option value="45" {{ old('durasi', $schedule->durasi) == 45 ? 'selected' : '' }}>45 menit</option>
                                <option value="60" {{ old('durasi', $schedule->durasi) == 60 ? 'selected' : '' }}>60 menit</option>
                            </select>
                            @error('durasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('dokter.schedules.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
