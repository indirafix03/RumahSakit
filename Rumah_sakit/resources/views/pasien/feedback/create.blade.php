@extends('layouts.app')

@section('title', 'Beri Feedback')

@section('content')
<!-- Header Section -->
<div class="page-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Beri Feedback & Rating</h1>
                <p class="page-subtitle">Bagikan pengalaman konsultasi Anda dengan dokter</p>
            </div>
            <div class="text-white">
                <p class="mb-0 fw-medium">
                    <i class="fas fa-calendar-check me-2"></i>
                    Janji Temu Selesai
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Appointment Info Card -->
            <div class="content-card mb-4">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">
                        <i class="fas fa-info-circle me-2"></i>Detail Janji Temu
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-primary">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Dokter</label>
                                    <p class="info-value">Dr. {{ $appointment->dokter->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-success">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Tanggal</label>
                                    <p class="info-value">{{ $appointment->tanggal_booking->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-info">
                                    <i class="fas fa-clinic-medical"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Poli</label>
                                    <p class="info-value">{{ $appointment->dokter->poli->nama_poli ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-warning">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Status</label>
                                    <p class="info-value">
                                        <span class="status-badge completed">Selesai</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Form Card -->
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">
                        <i class="fas fa-star me-2"></i>Feedback & Rating
                    </h5>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('pasien.feedback.store', $appointment->id) }}" method="POST" id="feedbackForm">
                        @csrf
                        
                        <!-- Rating Section -->
                        <div class="rating-section mb-4">
                            <label class="form-label-custom mb-3">
                                <strong>Berikan Rating</strong> 
                                <span class="text-danger">*</span>
                            </label>
                            
                            <div class="rating-container">
                                <div class="stars-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                               class="star-input" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <label for="star{{ $i }}" class="star-label">
                                            <i class="fas fa-star"></i>
                                            <span class="star-text">{{ $i }} bintang</span>
                                        </label>
                                    @endfor
                                </div>
                                <div class="rating-preview mt-2">
                                    <small class="text-muted" id="ratingDescription">
                                        Pilih rating dengan mengklik bintang
                                    </small>
                                </div>
                            </div>
                            @error('rating')
                                <div class="error-message text-danger mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Review Section -->
                        <div class="review-section mb-4">
                            <label for="ulasan" class="form-label-custom">
                                <strong>Tulis Ulasan</strong> 
                                <span class="text-danger">*</span>
                            </label>
                            <div class="form-group-custom">
                                <textarea class="form-control-custom @error('ulasan') is-invalid-custom @enderror" 
                                        id="ulasan" 
                                        name="ulasan" 
                                        rows="6" 
                                        placeholder="Bagaimana pengalaman konsultasi Anda dengan dokter? Ceritakan tentang pelayanan, komunikasi, dan kepuasan Anda..."
                                        required>{{ old('ulasan') }}</textarea>
                                <div class="form-meta">
                                    <span class="char-count">
                                        <span id="charCount">0</span>/1000 karakter
                                    </span>
                                    <span class="form-help">
                                        <i class="fas fa-info-circle me-1"></i>Minimal 10 karakter
                                    </span>
                                </div>
                            </div>
                            @error('ulasan')
                                <div class="error-message text-danger mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('pasien.dashboard') }}" class="btn btn-secondary-custom">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Feedback
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('ulasan');
    const charCount = document.getElementById('charCount');
    const ratingInputs = document.querySelectorAll('.star-input');
    const ratingDescription = document.getElementById('ratingDescription');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('feedbackForm');

    // Character count for textarea
    textarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count < 10) {
            charCount.classList.add('text-danger');
            charCount.classList.remove('text-success');
        } else {
            charCount.classList.remove('text-danger');
            charCount.classList.add('text-success');
        }
    });

    // Initialize character count
    charCount.textContent = textarea.value.length;

    // Rating descriptions
    const ratingDescriptions = {
        1: 'Sangat Tidak Puas - Pengalaman yang kurang memuaskan',
        2: 'Tidak Puas - Ada beberapa hal yang perlu ditingkatkan',
        3: 'Cukup Puas - Pengalaman biasa saja',
        4: 'Puas - Pengalaman yang baik dan memuaskan',
        5: 'Sangat Puas - Pengalaman luar biasa dan sangat memuaskan'
    };

    // Rating input change handler
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            const rating = this.value;
            ratingDescription.textContent = ratingDescriptions[rating] || 'Pilih rating dengan mengklik bintang';
            ratingDescription.classList.add('text-primary');
        });
    });

    // Form validation before submit
    form.addEventListener('submit', function(e) {
        const rating = document.querySelector('input[name="rating"]:checked');
        const review = textarea.value.trim();
        
        if (!rating) {
            e.preventDefault();
            showAlert('warning', 'Harap berikan rating terlebih dahulu');
            return;
        }
        
        if (review.length < 10) {
            e.preventDefault();
            showAlert('warning', 'Ulasan harus minimal 10 karakter');
            return;
        }
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
    });

    function showAlert(type, message) {
        // Remove existing alerts
        const existingAlert = document.querySelector('.submit-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        const alert = document.createElement('div');
        alert.className = `submit-alert alert-custom alert-${type}-custom mt-3`;
        alert.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-3 fs-4"></i>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        form.insertBefore(alert, form.querySelector('.form-actions'));
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentElement) {
                alert.remove();
            }
        }, 5000);
    }
});
</script>

<style>
/* Info Items */
.info-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.info-item:last-child {
    border-bottom: none;
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 1rem;
    font-size: 1.2rem;
}

.info-content {
    flex: 1;
}

.info-label {
    font-size: 0.85rem;
    color: var(--light-text);
    margin-bottom: 0.25rem;
    display: block;
}

.info-value {
    font-weight: 600;
    color: var(--dark-text);
    margin: 0;
}

.status-badge {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.completed {
    background: var(--gradient-success);
    color: white;
}

/* Rating Section */
.rating-container {
    text-align: center;
}

.stars-rating {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.star-input {
    display: none;
}

.star-label {
    cursor: pointer;
    padding: 1rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    background: rgba(0, 0, 0, 0.02);
}

.star-label:hover {
    background: rgba(255, 193, 7, 0.1);
    transform: scale(1.1);
}

.star-input:checked + .star-label {
    background: var(--gradient-warning);
    border-color: var(--warning);
    transform: scale(1.1);
}

.star-label i {
    font-size: 2rem;
    color: #ddd;
    transition: all 0.3s ease;
}

.star-label:hover i,
.star-input:checked + .star-label i {
    color: var(--warning);
}

.star-text {
    display: block;
    font-size: 0.8rem;
    margin-top: 0.5rem;
    color: var(--light-text);
}

.star-input:checked + .star-label .star-text {
    color: white;
    font-weight: 600;
}

.rating-preview {
    min-height: 24px;
}

/* Review Section */
.form-group-custom {
    position: relative;
}

.form-control-custom {
    min-height: 120px;
    resize: vertical;
}

.form-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
    font-size: 0.8rem;
}

.char-count {
    font-weight: 600;
}

.form-help {
    color: var(--light-text);
}

/* Error States */
.is-invalid-custom {
    border-color: var(--danger) !important;
    background: rgba(239, 68, 68, 0.05);
}

.error-message {
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

/* Form Actions */
.form-actions {
    padding-top: 1.5rem;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

/* Submit Alert */
.submit-alert {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .stars-rating {
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .star-label {
        padding: 0.75rem;
        flex: 1;
        min-width: 80px;
    }
    
    .star-label i {
        font-size: 1.5rem;
    }
    
    .info-item {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem 0;
    }
    
    .info-icon {
        margin-right: 0;
        margin-bottom: 0.75rem;
    }
    
    .form-actions .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .stars-rating {
        flex-direction: column;
        align-items: center;
    }
    
    .star-label {
        width: 100%;
        max-width: 200px;
    }
}
</style>
@endpush