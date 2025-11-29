@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Admin Navigation -->
    <nav class="admin-navbar navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard">
                {{ config('app.name', 'MedicalSystem') }} - Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/users">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/polis">Poli</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/medicines">Obat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/appointments">Janji Temu</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="me-3 fw-medium">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="btn btn-logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        <!-- Page Header -->
        <div class="page-header">
            <div class="container position-relative">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="page-title">Edit User</h1>
                        <p class="page-subtitle mb-0">Update informasi pengguna</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary-custom">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar User
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-user-edit me-2"></i>
                        Form Edit User
                        <span class="section-badge">{{ $user->name }}</span>
                    </h2>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="name" class="form-label-custom">
                                        <i class="fas fa-user me-2"></i>Nama Lengkap
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control-custom @error('name') is-invalid-custom @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" 
                                           placeholder="Masukkan nama lengkap" required>
                                    @error('name')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="email" class="form-label-custom">
                                        <i class="fas fa-envelope me-2"></i>Alamat Email
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control-custom @error('email') is-invalid-custom @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" 
                                           placeholder="Masukkan alamat email" required>
                                    @error('email')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="password" class="form-label-custom">
                                        <i class="fas fa-lock me-2"></i>Password Baru
                                    </label>
                                    <input type="password" class="form-control-custom @error('password') is-invalid-custom @enderror" 
                                           id="password" name="password" 
                                           placeholder="Masukkan password baru">
                                    <div class="form-help-text">
                                        Kosongkan jika tidak ingin mengubah password
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="password_confirmation" class="form-label-custom">
                                        <i class="fas fa-lock me-2"></i>Konfirmasi Password Baru
                                    </label>
                                    <input type="password" class="form-control-custom" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Konfirmasi password baru">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="role" class="form-label-custom">
                                        <i class="fas fa-user-tag me-2"></i>Role Pengguna
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control-custom @error('role') is-invalid-custom @enderror" 
                                            id="role" name="role" required>
                                        <option value="">Pilih Role Pengguna</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="dokter" {{ old('role', $user->role) == 'dokter' ? 'selected' : '' }}>Dokter</option>
                                        <option value="pasien" {{ old('role', $user->role) == 'pasien' ? 'selected' : '' }}>Pasien</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4" id="poli-field" style="{{ old('role', $user->role) == 'dokter' ? '' : 'display: none;' }}">
                                    <label for="poli_id" class="form-label-custom">
                                        <i class="fas fa-hospital me-2"></i>Poli
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control-custom @error('poli_id') is-invalid-custom @enderror" 
                                            id="poli_id" name="poli_id" {{ old('role', $user->role) == 'dokter' ? 'required' : '' }}>
                                        <option value="">Pilih Poli</option>
                                        @foreach($polis as $poli)
                                            <option value="{{ $poli->id }}" 
                                                {{ old('poli_id', $user->poli_id) == $poli->id ? 'selected' : '' }}>
                                                {{ $poli->nama_poli }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('poli_id')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                    <div class="form-help-text">
                                        Hanya diperlukan untuk role Dokter
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary-custom">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
:root {
    --primary-text: #094fa4;
    --secondary-color: #0065c1;
    --card-color: #009ee5;
    --background-color: #52bcec;
    --light-bg: #f8f9fa;
    --dark-text: #2c3e50;
    --light-text: #7f8c8d;
    --white: #ffffff;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #3b82f6;
}

body {
    font-family: 'Oswald', sans-serif;
    color: var(--primary-text);
    line-height: 1.6;
    font-weight: 400;
    background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
    min-height: 100vh;
}

.admin-navbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 1rem 0;
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.8rem;
    background: linear-gradient(135deg, var(--primary-text) 0%, var(--secondary-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 0.5px;
}

.nav-link {
    font-weight: 500;
    color: var(--primary-text);
    margin: 0 0.5rem;
    letter-spacing: 0.5px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    transition: width 0.3s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 100%;
}

.nav-link.active {
    color: var(--secondary-color);
    font-weight: 600;
}

.btn-logout {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    color: var(--white);
    border: none;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
}

.page-header {
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    color: var(--white);
    padding: 2rem 0;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,50 1000,100 0,100"/></svg>');
    background-size: cover;
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    position: relative;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 300;
}

/* Section Cards */
.section-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 
        0 8px 32px rgba(0, 101, 193, 0.1),
        0 2px 8px rgba(0, 101, 193, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.section-card:hover {
    box-shadow: 
        0 12px 40px rgba(0, 101, 193, 0.15),
        0 4px 12px rgba(0, 101, 193, 0.1);
}

.section-header {
    background: linear-gradient(135deg, rgba(0, 101, 193, 0.05) 0%, rgba(0, 158, 229, 0.05) 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    color: var(--primary-text);
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
}

.section-badge {
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    color: var(--white);
    padding: 0.4rem 1rem;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.8rem;
    margin-left: 0.8rem;
}

.section-body {
    padding: 2rem;
}

/* Button Styles */
.btn-primary-custom {
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    color: var(--white);
    border: none;
    padding: 0.8rem 2rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 101, 193, 0.2);
    text-transform: uppercase;
    font-size: 0.9rem;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 101, 193, 0.3);
    color: var(--white);
}

.btn-secondary-custom {
    background: rgba(255, 255, 255, 0.9);
    color: var(--primary-text);
    border: 1px solid rgba(0, 101, 193, 0.2);
    padding: 0.8rem 2rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 12px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    font-size: 0.9rem;
}

.btn-secondary-custom:hover {
    background: rgba(255, 255, 255, 1);
    border-color: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 101, 193, 0.1);
    color: var(--primary-text);
}

/* Form Styles */
.form-group-custom {
    position: relative;
}

.form-label-custom {
    font-weight: 600;
    color: var(--primary-text);
    margin-bottom: 0.5rem;
    display: block;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
    text-transform: uppercase;
}

.form-label-custom .text-danger {
    color: var(--danger-color) !important;
}

.form-control-custom {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 101, 193, 0.2);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
    font-family: 'Oswald', sans-serif;
}

.form-control-custom:focus {
    background: rgba(255, 255, 255, 1);
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(0, 101, 193, 0.1);
    outline: none;
}

.form-control-custom::placeholder {
    color: var(--light-text);
    opacity: 0.7;
}

/* Invalid State */
.is-invalid-custom {
    border-color: var(--danger-color);
    background: rgba(239, 68, 68, 0.05);
}

.is-invalid-custom:focus {
    border-color: var(--danger-color);
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.invalid-feedback-custom {
    color: var(--danger-color);
    font-size: 0.85rem;
    margin-top: 0.25rem;
    font-weight: 500;
}

/* Form Help Text */
.form-help-text {
    font-size: 0.8rem;
    color: var(--light-text);
    margin-top: 0.25rem;
    font-style: italic;
}

/* Select Arrow */
.form-control-custom select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%230065c1' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 16px 12px;
    padding-right: 2.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.3rem;
    }
    
    .btn-primary-custom,
    .btn-secondary-custom {
        padding: 0.7rem 1.5rem;
        font-size: 0.85rem;
    }
    
    .section-body {
        padding: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const poliField = document.getElementById('poli-field');
    const poliSelect = document.getElementById('poli_id');

    function togglePoliField() {
        if (roleSelect.value === 'dokter') {
            poliField.style.display = 'block';
            poliSelect.setAttribute('required', 'required');
        } else {
            poliField.style.display = 'none';
            poliSelect.removeAttribute('required');
            poliSelect.value = '';
        }
    }

    // Initial check
    togglePoliField();

    // Add event listener
    roleSelect.addEventListener('change', togglePoliField);

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endsection