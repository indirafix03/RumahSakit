@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <main>
        <!-- Page Header -->
        <div class="page-header">
            <div class="container position-relative">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="page-title">Medicine Management</h1>
                        <p class="page-subtitle mb-0">Kelola data obat dan stok persediaan</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary-custom">
                            <i class="fas fa-plus me-2"></i> Tambah Obat Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert-custom alert-success-custom">
                    <div class="alert-content">
                        <i class="fas fa-check-circle alert-icon"></i>
                        <div class="alert-text">
                            <strong>Berhasil!</strong> {{ session('success') }}
                        </div>
                    </div>
                    <button type="button" class="alert-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Filter dan Search Section -->
            <div class="section-card mb-4">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-filter me-2"></i>
                        Filter & Pencarian
                    </h2>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.medicines.index') }}" method="GET">
                        <div class="row g-4">
                            <!-- Search Input -->
                            <div class="col-md-4">
                                <div class="form-group-custom">
                                    <label for="search" class="form-label-custom">Cari Obat</label>
                                    <div class="search-input-wrapper">
                                        <i class="fas fa-search search-icon"></i>
                                        <input type="text" 
                                               class="search-input" 
                                               id="search" 
                                               name="search" 
                                               placeholder="Cari berdasarkan nama atau deskripsi..."
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="col-md-3">
                                <div class="form-group-custom">
                                    <label for="status" class="form-label-custom">Status Stok</label>
                                    <select class="form-control-custom" id="status" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>
                                            Tersedia
                                        </option>
                                        <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>
                                            Stok Habis
                                        </option>
                                        <option value="kadaluarsa" {{ request('status') == 'kadaluarsa' ? 'selected' : '' }}>
                                            Kadaluarsa
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Type Filter -->
                            <div class="col-md-3">
                                <div class="form-group-custom">
                                    <label for="type" class="form-label-custom">Tipe Obat</label>
                                    <select class="form-control-custom" id="type" name="type">
                                        <option value="">Semua Tipe</option>
                                        <option value="keras" {{ request('type') == 'keras' ? 'selected' : '' }}>
                                            Keras
                                        </option>
                                        <option value="biasa" {{ request('type') == 'biasa' ? 'selected' : '' }}>
                                            Biasa
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="d-grid gap-2 w-100">
                                    <button type="submit" class="btn btn-primary-custom btn-sm">
                                        <i class="fas fa-filter me-2"></i> Filter
                                    </button>
                                    @if(request()->hasAny(['search', 'status', 'type']))
                                    <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary-custom btn-sm">
                                        <i class="fas fa-times me-2"></i> Reset
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Info -->
            @if(request()->hasAny(['search', 'status', 'type']))
            <div class="alert-custom alert-info-custom mb-4">
                <div class="alert-content">
                    <i class="fas fa-info-circle alert-icon"></i>
                    <div class="alert-text">
                        <strong>Hasil Filter:</strong>
                        @if(request('search'))
                            Pencarian: "{{ request('search') }}"
                        @endif
                        @if(request('status'))
                            @if(request('search')) • @endif
                            Status: 
                            @switch(request('status'))
                                @case('tersedia')
                                    Tersedia
                                    @break
                                @case('habis')
                                    Stok Habis
                                    @break
                                @case('kadaluarsa')
                                    Kadaluarsa
                                    @break
                            @endswitch
                        @endif
                        @if(request('type'))
                            @if(request('search') || request('status')) • @endif
                            Tipe: {{ ucfirst(request('type')) }}
                        @endif
                    </div>
                </div>
                <div class="alert-badge">
                    Total: {{ $medicines->count() }} obat
                </div>
            </div>
            @endif

            <!-- Medicines Table -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-pills me-2"></i>
                        Daftar Obat
                        @if($medicines->count() > 0)
                            <span class="section-badge">{{ $medicines->count() }} obat</span>
                        @endif
                    </h2>
                </div>
                <div class="section-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th width="80">Gambar</th>
                                    <th>Nama Obat</th>
                                    <th width="100">Tipe</th>
                                    <th width="100">Stok</th>
                                    <th width="120">Status Stok</th>
                                    <th width="140">Tanggal Kadaluarsa</th>
                                    <th>Deskripsi</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicines as $medicine)
                                <tr>
                                    <td class="fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($medicine->gambar_obat && Storage::disk('public')->exists($medicine->gambar_obat))
                                            <img src="{{ Storage::url($medicine->gambar_obat) }}" 
                                                 class="medicine-image"
                                                 alt="{{ $medicine->nama_obat }}">
                                        @else
                                            <div class="medicine-image-placeholder">
                                                <i class="fas fa-pills"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="medicine-name">{{ $medicine->nama_obat }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-type badge-{{ $medicine->tipe_obat === 'keras' ? 'danger' : 'success' }}">
                                            {{ ucfirst($medicine->tipe_obat) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="stock-badge stock-{{ $medicine->stok > 10 ? 'high' : ($medicine->stok > 0 ? 'low' : 'empty') }}">
                                            {{ $medicine->stok }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusInfo = $medicine->getStatusInfo();
                                        @endphp
                                        <span class="status-badge status-{{ $statusInfo['color'] }}" title="{{ $statusInfo['reason'] }}">
                                            <i class="fas {{ $statusInfo['icon'] }} me-1"></i>
                                            {{ $statusInfo['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="expiry-date">
                                            {!! $medicine->getFormattedExpiredDate() !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="medicine-description">
                                            {{ Str::limit($medicine->deskripsi, 60) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- tombol edit -->
                                            <a href="{{ route('admin.medicines.edit', $medicine) }}" 
                                               class="btn-action btn-edit" title="Edit Obat">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- tombol delete -->
                                            <form action="{{ route('admin.medicines.destroy', $medicine) }}" 
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn-action btn-delete" 
                                                        title="Hapus Obat"
                                                        onclick="return confirm('Hapus obat {{ $medicine->nama_obat }}?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-pills empty-icon"></i>
                                            <h4 class="empty-title mt-3">
                                                @if(request()->hasAny(['search', 'status', 'type']))
                                                    Tidak ada obat yang sesuai dengan filter
                                                @else
                                                    Belum Ada Data Obat
                                                @endif
                                            </h4>
                                            <p class="empty-text">
                                                @if(request()->hasAny(['search', 'status', 'type']))
                                                    Coba ubah filter pencarian Anda atau 
                                                    <a href="{{ route('admin.medicines.index') }}">reset filter</a>
                                                @else
                                                    Mulai dengan menambahkan obat pertama Anda
                                                @endif
                                            </p>
                                            @if(!request()->hasAny(['search', 'status', 'type']))
                                            <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary-custom mt-3">
                                                <i class="fas fa-plus me-2"></i> Tambah Obat Pertama
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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

.btn-primary-custom.btn-sm {
    padding: 0.6rem 1.5rem;
    font-size: 0.8rem;
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

.btn-secondary-custom.btn-sm {
    padding: 0.6rem 1.5rem;
    font-size: 0.8rem;
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

/* Search Input */
.search-input-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--light-text);
    z-index: 1;
}

.search-input {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 101, 193, 0.2);
    border-radius: 12px;
    padding: 0.75rem 1rem 0.75rem 3rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
    font-family: 'Oswald', sans-serif;
}

.search-input:focus {
    background: rgba(255, 255, 255, 1);
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(0, 101, 193, 0.1);
    outline: none;
}

/* Alert Styles */
.alert-custom {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: between;
}

.alert-success-custom {
    border-left: 4px solid var(--success-color);
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0.1) 100%);
}

.alert-info-custom {
    border-left: 4px solid var(--info-color);
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.1) 100%);
    justify-content: space-between;
}

.alert-content {
    display: flex;
    align-items: center;
    flex-grow: 1;
}

.alert-icon {
    font-size: 1.2rem;
    margin-right: 1rem;
}

.alert-success-custom .alert-icon {
    color: var(--success-color);
}

.alert-info-custom .alert-icon {
    color: var(--info-color);
}

.alert-text {
    color: var(--dark-text);
    font-weight: 500;
}

.alert-badge {
    background: var(--info-color);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.alert-close {
    background: none;
    border: none;
    color: var(--light-text);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.alert-close:hover {
    background: rgba(0, 0, 0, 0.05);
    color: var(--dark-text);
}

/* Table Styles */
.table-custom {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(5px);
}

.table-custom thead th {
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    color: var(--white);
    font-weight: 600;
    letter-spacing: 0.5px;
    border: none;
    padding: 1rem;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.table-custom tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-color: rgba(0, 0, 0, 0.05);
}

.table-custom tbody tr:hover {
    background: rgba(0, 101, 193, 0.03);
}

/* Medicine Image */
.medicine-image {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid rgba(0, 101, 193, 0.1);
}

.medicine-image-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(0, 101, 193, 0.1) 0%, rgba(0, 158, 229, 0.1) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--light-text);
    border: 2px dashed rgba(0, 101, 193, 0.2);
}

.medicine-name {
    font-weight: 600;
    color: var(--primary-text);
}

.medicine-description {
    color: var(--light-text);
    font-size: 0.9rem;
}

/* Badge Styles */
.badge-type {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.badge-danger {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    color: white;
}

.badge-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #10b981 100%);
    color: white;
}

.stock-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.stock-high {
    background: linear-gradient(135deg, var(--success-color) 0%, #10b981 100%);
    color: white;
}

.stock-low {
    background: linear-gradient(135deg, var(--warning-color) 0%, #f59e0b 100%);
    color: white;
}

.stock-empty {
    background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
    color: white;
}

.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
}

.status-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #10b981 100%);
    color: white;
}

.status-warning {
    background: linear-gradient(135deg, var(--warning-color) 0%, #f59e0b 100%);
    color: white;
}

.status-danger {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    color: white;
}

.status-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
    color: white;
}

.expiry-date {
    font-size: 0.85rem;
    font-weight: 500;
}

/* Action Buttons */
.btn-action {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 1px solid rgba(0, 101, 193, 0.2);
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.8);
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-edit {
    color: var(--warning-color);
    border-color: rgba(245, 158, 11, 0.3);
}

.btn-edit:hover {
    background: rgba(245, 158, 11, 0.1);
    border-color: var(--warning-color);
}

.btn-delete {
    color: var(--danger-color);
    border-color: rgba(239, 68, 68, 0.3);
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.1);
    border-color: var(--danger-color);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-text);
    margin-bottom: 1rem;
}

.empty-text {
    color: var(--light-text);
    font-size: 1rem;
    margin-bottom: 0;
}

.empty-text a {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 600;
}

.empty-text a:hover {
    text-decoration: underline;
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
    
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .medicine-name {
        font-size: 0.9rem;
    }
    
    .medicine-description {
        font-size: 0.8rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-custom');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endsection