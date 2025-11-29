@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Page Content -->
    <main>
        <!-- Page Header -->
        <div class="page-header">
            <div class="container position-relative">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="page-title">Manajemen Rekam Medis</h1>
                        <p class="page-subtitle mb-0">Kelola semua data rekam medis pasien</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('dokter.medical-records.create') }}" class="btn btn-primary-custom">
                            <i class="fas fa-plus me-2"></i>Buat Rekam Medis Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close-custom" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close-custom" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Medical Records Table -->
            <div class="section-card">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2 class="section-title">
                        <i class="fas fa-file-medical me-2"></i>Daftar Rekam Medis
                        <span class="section-badge">{{ $medicalRecords->count() }}</span>
                    </h2>
                    <div class="text-muted small">
                        Total {{ $medicalRecords->count() }} rekam medis
                    </div>
                </div>
                <div class="section-body p-0">
                    @if($medicalRecords->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-file-medical fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada rekam medis</h5>
                            <p class="text-muted">Mulai dengan membuat rekam medis baru untuk pasien.</p>
                            <a href="{{ route('dokter.medical-records.create') }}" class="btn btn-primary-custom mt-3">
                                <i class="fas fa-plus me-2"></i>Buat Rekam Medis Pertama
                            </a>
                        </div>
                    @else
                        <div class="table-responsive-custom">
                            <table class="table-custom">
                                <thead class="table-custom-header">
                                    <tr>
                                        <th width="5%">NO</th>
                                        <th width="20%">Pasien</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="20%">Diagnosis</th>
                                        <th width="20%">Tindakan Medis</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicalRecords as $record)
                                    <tr class="table-custom-row">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $record->pasien->name }}</strong>
                                            <br>
                                            <small class="text-muted">ID: {{ $record->pasien->id }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $record->created_at->format('d/m/Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $record->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                  title="{{ $record->diagnosis }}">
                                                {{ Str::limit($record->diagnosis, 50) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                  title="{{ $record->tindakan_medis }}">
                                                {{ Str::limit($record->tindakan_medis, 50) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- View Button -->
                                                <a href="{{ route('dokter.medical-records.show', $record) }}" 
                                                   class="btn btn-sm btn-info-custom" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Edit Button -->
                                                <a href="{{ route('dokter.medical-records.edit', $record) }}" 
                                                   class="btn btn-sm btn-warning-custom" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('dokter.medical-records.destroy', $record) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger-custom" 
                                                            onclick="return confirm('Yakin ingin menghapus rekam medis ini?')"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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

.btn-info-custom {
    background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
    color: var(--white);
    border: none;
    padding: 0.5rem 1rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}

.btn-info-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    color: var(--white);
}

.btn-warning-custom {
    background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
    color: var(--white);
    border: none;
    padding: 0.5rem 1rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
}

.btn-warning-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    color: var(--white);
}

.btn-danger-custom {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    color: var(--white);
    border: none;
    padding: 0.5rem 1rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
}

.btn-danger-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    color: var(--white);
}

/* Alert Styles */
.alert-success-custom {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 12px;
    color: var(--success-color);
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

.alert-danger-custom {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 12px;
    color: var(--danger-color);
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

/* Table Styles */
.table-responsive-custom {
    overflow-x: auto;
    border-radius: 12px;
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0;
}

.table-custom-header {
    background: linear-gradient(135deg, rgba(0, 101, 193, 0.1) 0%, rgba(0, 158, 229, 0.05) 100%);
}

.table-custom-header th {
    padding: 1rem 1.5rem;
    font-weight: 600;
    color: var(--primary-text);
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 1px solid rgba(0, 101, 193, 0.1);
}

.table-custom-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.table-custom-row:hover {
    background: rgba(0, 101, 193, 0.03);
}

.table-custom-row td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
}

.btn-close-custom {
    background: none;
    border: none;
    font-size: 1.2rem;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.btn-close-custom:hover {
    opacity: 1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.3rem;
    }
    
    .btn-primary-custom {
        padding: 0.7rem 1.5rem;
        font-size: 0.85rem;
    }
    
    .section-body {
        padding: 1.5rem;
    }
    
    .table-custom-header th,
    .table-custom-row td {
        padding: 0.75rem 1rem;
    }
}
</style>
@endsection