@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-star me-2"></i>Feedback Pasien
        </h1>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($averageRating, 1) }}/5</h4>
                            <p class="mb-0">Rating Rata-rata</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalFeedback }}</h4>
                            <p class="mb-0">Total Feedback</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-comments fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $feedbacks->where('rating', '>=', 4)->count() }}</h4>
                            <p class="mb-0">Feedback Positif</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-thumbs-up fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $feedbacks->where('rating', '<=', 2)->count() }}</h4>
                            <p class="mb-0">Perlu Perhatian</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="card">
        <div class="card-body">
            @if($feedbacks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Rating</th>
                                <th>Ulasan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedbacks as $feedback)
                            <tr>
                                <td>{{ $feedback->patient->name }}</td>
                                <td>dr. {{ $feedback->doctor->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning me-1">
                                            {{ str_repeat('⭐', $feedback->rating) }}
                                        </span>
                                        <span class="badge bg-primary">{{ $feedback->rating }}/5</span>
                                    </div>
                                </td>
                                <td>
                                    @if($feedback->ulasan)
                                        {{ Str::limit($feedback->ulasan, 80) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $feedback->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#feedbackModal{{ $feedback->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="{{ route('admin.feedback.destroy', $feedback->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus feedback ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div class="modal fade" id="feedbackModal{{ $feedback->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Feedback</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Pasien:</strong> {{ $feedback->patient->name }}</p>
                                                    <p><strong>Dokter:</strong> dr. {{ $feedback->doctor->name }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Rating:</strong><br>
                                                        <span class="text-warning">
                                                            {{ str_repeat('⭐', $feedback->rating) }}
                                                        </span>
                                                        ({{ $feedback->rating }}/5)
                                                    </p>
                                                    <p><strong>Tanggal:</strong> {{ $feedback->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <strong>Ulasan:</strong>
                                                <div class="border rounded p-3 mt-1 bg-light">
                                                    {{ $feedback->ulasan ?? 'Tidak ada ulasan' }}
                                                </div>
                                            </div>
                                            @if($feedback->appointment)
                                            <div class="mt-3">
                                                <strong>Janji Temu:</strong><br>
                                                Tanggal: {{ $feedback->appointment->tanggal_booking->format('d/m/Y') }}<br>
                                                Keluhan: {{ $feedback->appointment->keluhan_singkat }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-star fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum Ada Feedback</h4>
                    <p class="text-muted">Belum ada feedback dari pasien</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection