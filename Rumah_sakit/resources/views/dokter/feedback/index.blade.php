@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Feedback Pasien</h1>
            <p class="text-muted">Ulasan dan rating dari pasien Anda</p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Feedback
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalFeedback }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Rating Rata-rata
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($averageRating, 1) }}/5
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Feedback -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Daftar Feedback
            </h6>
        </div>
        <div class="card-body">
            @if($feedbacks->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($feedbacks as $feedback)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1">{{ $feedback->pasien->name ?? 'Tidak Diketahui' }}</h6>
                                <small class="text-muted">
                                    {{ $feedback->created_at->translatedFormat('d F Y H:i') }}
                                </small>
                            </div>
                            <div class="text-warning">
                                {{ $feedback->rating ?? '-' }}
                            </div>
                        </div>
                        
                        @if($feedback->ulasan)
                        <p class="mb-2">{{ $feedback->ulasan }}</p>
                        @else
                        <p class="mb-2 text-muted fst-italic">Tidak ada ulasan</p>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Janji Temu: {{ ($feedback->appointment && $feedback->appointment->tanggal_booking) ? $feedback->appointment->tanggal_booking->format('d/m/Y') : '-' }}
                            </small>
                            <form action="{{ route('dokter.feedback.destroy', $feedback->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus feedback ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada feedback dari pasien</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection