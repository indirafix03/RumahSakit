@extends('layouts.app')

@section('title', 'Beri Feedback')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Beri Feedback & Rating</h5>
                </div>
                <div class="appointment-info mb-4 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Detail Janji Temu:</h6>
                        <span class="badge bg-info">Status: Selesai</span>
                    </div>
                    <p class="mb-1"><strong>Dokter:</strong> Dr. {{ $appointment->dokter->name }}</p>
                    <p class="mb-1"><strong>Tanggal:</strong> {{ $appointment->tanggal_booking->format('d F Y') }}</p>
                    <p class="mb-0"><strong>Poli:</strong> {{ $appointment->dokter->poli->nama_poli ?? 'N/A' }}</p>
                </div>
                    <form action="{{ route('pasien.feedback.store', $appointment->id) }}" method="POST">
                        @csrf
                        
                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label"><strong>Rating</strong> <span class="text-danger">*</span></label>
                            <div class="rating-stars">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" required>
                                        <label class="form-check-label" for="rating{{ $i }}">
                                            @for($j = 1; $j <= $i; $j++)
                                                ⭐
                                            @endfor
                                            ({{ $i }})
                                        </label>
                                    </div>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ulasan" class="form-label"><strong>Ulasan</strong> <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('ulasan') is-invalid @enderror" 
                                    id="ulasan" 
                                    name="ulasan" 
                                    rows="5" 
                                    placeholder="Bagaimana pengalaman konsultasi Anda dengan dokter? (minimal 10 karakter)"
                                    required>{{ old('ulasan') }}</textarea>
                            <div class="form-text">Minimal 10 karakter, maksimal 1000 karakter.</div>
                            @error('ulasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('pasien.dashboard') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection