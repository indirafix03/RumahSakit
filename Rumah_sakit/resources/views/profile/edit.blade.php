@extends('layouts.app')

@section('title', 'Edit Profil - ' . Auth::user()->role)

@section('content')
<!-- Header Section -->
<div class="page-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Profil {{ ucfirst(Auth::user()->role) }}</h1>
                <p class="page-subtitle">Kelola informasi profil dan akun Anda</p>
            </div>
            <div class="text-white">
                <p class="mb-0 fw-medium">
                    <i class="{{ Auth::user()->role === 'dokter' ? 'fas fa-user-md' : 'fas fa-user' }} me-2"></i>
                    {{ ucfirst(Auth::user()->role) }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <strong>Sukses!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <strong>Error!</strong> Terdapat kesalahan dalam input data.
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <div class="row justify-content-center">
        <!-- Left Column - Profile Info -->
        <div class="col-lg-4 col-md-5 mb-4">
            <!-- Profile Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-4">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="avatar-placeholder {{ Auth::user()->role === 'dokter' ? 'bg-primary' : 'bg-success' }} text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                             style="width: 100px; height: 100px; font-size: 2rem;">
                            <i class="{{ Auth::user()->role === 'dokter' ? 'fas fa-user-md' : 'fas fa-user' }}"></i>
                        </div>
                        <div class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-2 border-white">
                            <i class="fas fa-check text-white" style="font-size: 0.6rem;"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-1">{{ Auth::user()->name }}</h5>
                    
                    @if(Auth::user()->role === 'dokter')
                        <p class="text-muted mb-2 small">{{ Auth::user()->spesialisasi ?? 'Dokter Umum' }}</p>
                    @else
                        <p class="text-muted mb-2 small">Pasien</p>
                    @endif
                    
                    <div class="badge {{ Auth::user()->role === 'dokter' ? 'bg-primary' : 'bg-success' }} mb-3">
                        <i class="{{ Auth::user()->role === 'dokter' ? 'fas fa-stethoscope' : 'fas fa-heart' }} me-1"></i>
                        {{ ucfirst(Auth::user()->role) }}
                    </div>
                    
                    @if(Auth::user()->poli && Auth::user()->role === 'dokter')
                    <div class="d-flex align-items-center justify-content-center text-muted mb-3 small">
                        <i class="fas fa-clinic-medical me-2"></i>
                        <span>{{ Auth::user()->poli->nama_poli }}</span>
                    </div>
                    @endif

                    <!-- Contact Info -->
                    <div class="text-start mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-primary me-2 small"></i>
                            <span class="text-muted small">{{ Auth::user()->email }}</span>
                        </div>
                        @if(Auth::user()->no_telepon)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-primary me-2 small"></i>
                            <span class="text-muted small">{{ Auth::user()->no_telepon }}</span>
                        </div>
                        @endif
                        @if(Auth::user()->alamat)
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt text-primary me-2 mt-1 small"></i>
                            <span class="text-muted small">{{ Str::limit(Auth::user()->alamat, 50) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent py-2">
                    <h6 class="mb-0 small">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistik {{ Auth::user()->role === 'dokter' ? 'Praktik' : 'Kesehatan' }}
                    </h6>
                </div>
                <div class="card-body p-3">
                    @if(Auth::user()->isDokter())
                    <div class="row text-center g-2">
                        <div class="col-6">
                            <div class="border-end pe-2">
                                <h6 class="text-primary mb-0 fw-bold">{{ Auth::user()->appointments_as_dokter_count ?? 0 }}</h6>
                                <small class="text-muted">Total Janji</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success mb-0 fw-bold">{{ Auth::user()->patients_count ?? 0 }}</h6>
                            <small class="text-muted">Pasien</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end pe-2">
                                <h6 class="text-info mb-0 fw-bold">{{ Auth::user()->active_appointments_count ?? 0 }}</h6>
                                <small class="text-muted">Aktif</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning mb-0 fw-bold">{{ Auth::user()->completed_appointments_count ?? 0 }}</h6>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>
                    @elseif(Auth::user()->isPasien())
                    <div class="row text-center g-2">
                        <div class="col-6">
                            <div class="border-end pe-2">
                                <h6 class="text-primary mb-0 fw-bold">{{ Auth::user()->appointments_as_pasien_count ?? 0 }}</h6>
                                <small class="text-muted">Kunjungan</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success mb-0 fw-bold">{{ Auth::user()->doctors_count ?? 0 }}</h6>
                            <small class="text-muted">Dokter</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end pe-2">
                                <h6 class="text-info mb-0 fw-bold">{{ Auth::user()->upcoming_appointments_count ?? 0 }}</h6>
                                <small class="text-muted">Mendatang</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning mb-0 fw-bold">{{ Auth::user()->completed_appointments_count ?? 0 }}</h6>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Edit Forms -->
        <div class="col-lg-8 col-md-7">
            <!-- Update Profile Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2 text-primary"></i>
                        Informasi Profil
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no_telepon" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                       id="no_telepon" name="no_telepon" value="{{ old('no_telepon', Auth::user()->no_telepon) }}"
                                       placeholder="Contoh: 081234567890">
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(Auth::user()->role === 'dokter')
                            <div class="col-md-6 mb-3">
                                <label for="spesialisasi" class="form-label">Spesialisasi</label>
                                <input type="text" class="form-control @error('spesialisasi') is-invalid @enderror" 
                                       id="spesialisasi" name="spesialisasi" value="{{ old('spesialisasi', Auth::user()->spesialisasi) }}"
                                       placeholder="Contoh: Dokter Umum, Spesialis Jantung, dll.">
                                @error('spesialisasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="3" 
                                      placeholder="{{ Auth::user()->role === 'dokter' ? 'Alamat lengkap praktik' : 'Alamat tempat tinggal' }}">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(Auth::user()->role === 'dokter')
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio / Deskripsi Profesional</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="4" 
                                      placeholder="Deskripsikan latar belakang pendidikan, pengalaman, dan keahlian Anda">{{ old('bio', Auth::user()->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Deskripsi ini akan ditampilkan kepada pasien.</div>
                        </div>
                        @else
                        <div class="mb-3">
                            <label for="bio" class="form-label">Informasi Tambahan</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="4" 
                                      placeholder="Informasi kesehatan tambahan, alergi, atau catatan penting lainnya">{{ old('bio', Auth::user()->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Informasi ini akan membantu dokter dalam memberikan pelayanan yang lebih baik.</div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lock me-2 text-primary"></i>
                        Ubah Password
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </button>
                            <small class="text-muted">Gunakan password yang kuat dan mudah diingat</small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card shadow-sm border-0 border-danger">
                <div class="card-header bg-transparent py-3 border-danger">
                    <h5 class="card-title mb-0 text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Hapus Akun
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Setelah akun Anda dihapus, semua data dan resource akan dihapus secara permanen. 
                        @if(Auth::user()->role === 'dokter')
                        Data janji temu, riwayat pasien, dan informasi praktik akan hilang.
                        @else
                        Data janji temu, riwayat kesehatan, dan rekam medis akan hilang.
                        @endif
                        Sebelum menghapus akun, harap unduh data atau informasi yang ingin Anda simpan.
                    </p>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="fas fa-trash me-2"></i>Hapus Akun
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Konfirmasi Penghapusan Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.</p>
                <p class="text-danger fw-bold">
                    <i class="fas fa-info-circle me-2"></i>
                    @if(Auth::user()->role === 'dokter')
                    Semua data termasuk janji temu, riwayat pasien, dan informasi praktik akan dihapus secara permanen.
                    @else
                    Semua data termasuk janji temu, riwayat kesehatan, dan rekam medis akan dihapus secara permanen.
                    @endif
                </p>
                <div class="mt-3">
                    <label for="delete_confirmation" class="form-label">
                        Ketik <strong>"HAPUS"</strong> untuk konfirmasi:
                    </label>
                    <input type="text" class="form-control" id="delete_confirmation" 
                           placeholder="Ketik HAPUS di sini">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('profile.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        <i class="fas fa-trash me-2"></i>Hapus Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.avatar-placeholder.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}

.card {
    border-radius: 12px;
}

.form-control:focus {
    border-color: #094fa4;
    box-shadow: 0 0 0 0.2rem rgba(9, 79, 164, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #094fa4 0%, #0065c1 100%);
    border: none;
    border-radius: 8px;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(9, 79, 164, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0 15px;
    }
    
    .page-header {
        margin: -1rem -15px 2rem -15px;
        border-radius: 0 0 20px 20px;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .row.g-2 > [class*="col-"] {
        margin-bottom: 0.5rem;
    }
}

/* Ensure proper spacing */
.row.justify-content-center {
    margin: 0 -10px;
}

.col-lg-4, .col-lg-8, .col-md-5, .col-md-7 {
    padding: 0 10px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete account confirmation
    const deleteConfirmation = document.getElementById('delete_confirmation');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    if (deleteConfirmation && confirmDeleteBtn) {
        deleteConfirmation.addEventListener('input', function() {
            confirmDeleteBtn.disabled = this.value !== 'HAPUS';
        });
    }

    // Character counter for bio
    const bioTextarea = document.getElementById('bio');
    if (bioTextarea) {
        const charCount = document.createElement('small');
        charCount.className = 'text-muted mt-1 d-block';
        charCount.textContent = `${bioTextarea.value.length} karakter`;
        
        bioTextarea.parentNode.appendChild(charCount);
        
        bioTextarea.addEventListener('input', function() {
            charCount.textContent = `${this.value.length} karakter`;
        });
    }

    // Phone number formatting
    const phoneInput = document.getElementById('no_telepon');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });
    }
});
</script>
@endpush