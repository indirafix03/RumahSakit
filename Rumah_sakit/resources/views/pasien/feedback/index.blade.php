@extends('layouts.app')

@section('title', 'Feedback Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Feedback Saya</h1>
        </div>
    </div>

    @if($feedbacks->isEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada feedback</h5>
                    <p class="text-muted">Anda belum memberikan feedback untuk janji temu manapun.</p>
                    <a href="{{ route('pasien.appointments.index') }}" class="btn btn-primary">
                        <i class="fas fa-calendar me-1"></i> Lihat Janji Temu
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        @foreach($feedbacks as $feedback)
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Dr. {{ $feedback->dokter->name }}
                    </h6>
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $feedback->rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $feedback->ulasan }}</p>
                    <div class="text-muted small">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $feedback->created_at->format('d F Y H:i') }}
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <small class="text-muted">
                        Poli: {{ $feedback->dokter->poli->nama_poli ?? 'N/A' }} | 
                        Tanggal Janji: {{ ($feedback->appointment && $feedback->appointment->tanggal_booking) ? $feedback->appointment->tanggal_booking->format('d M Y') : '-' }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection