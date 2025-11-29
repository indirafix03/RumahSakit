@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <main>
        <!-- Page Header (match admin dashboard style) -->
        <div class="page-header">
            <div class="container position-relative">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="page-title">Data Pasien</h1>
                        <p class="page-subtitle mb-0">Kelola pengguna dan akses sistem</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary-custom">
                            <i class="fas fa-plus me-2"></i> Tambah Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- User Management Section -->
            <div class="section-card">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2 class="section-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Daftar Pengguna
                        <span class="badge bg-primary">{{ $users->total() }}</span>
                    </h2>
                </div>

                <!-- Filter Section -->
                <div class="section-body border-bottom">
                    <form action="{{ route('admin.users.index') }}" method="GET" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Cari Pengguna</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Nama atau Email..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" onchange="this.form.submit()">
                                    <option value="">Semua Role</option>
                                    <option value="pasien" {{ request('role') == 'pasien' ? 'selected' : '' }}>Pasien</option>
                                    <option value="dokter" {{ request('role') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 me-2">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-refresh me-2"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="section-body p-0">
                    @if($users->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada pengguna</h5>
                            <p class="text-muted">Mulai dengan menambahkan pengguna baru</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary-custom mt-3">
                                <i class="fas fa-plus me-2"></i> Tambah Pengguna Pertama
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Nama Pengguna</th>
                                        <th width="20%">Email</th>
                                        <th width="15%">Role</th>
                                        <th width="15%">Poli / Spesialisasi</th>
                                        <th width="15%">Tanggal Dibuat</th>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2" style="width: 32px; height: 32px; background: linear-gradient(135deg, #0065c1 0%, #009ee5 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <strong>{{ $user->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $roleBadges = [
                                                    'admin' => 'danger',
                                                    'dokter' => 'warning',
                                                    'pasien' => 'success'
                                                ];
                                                $roleIcons = [
                                                    'admin' => 'shield-alt',
                                                    'dokter' => 'stethoscope',
                                                    'pasien' => 'user-circle'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $roleBadges[$user->role] ?? 'secondary' }}">
                                                <i class="fas fa-{{ $roleIcons[$user->role] ?? 'circle' }} me-1"></i>
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $user->poli ? $user->poli->nama_poli : '-' }}
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $user->created_at->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Edit Button -->
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit Pengguna">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Yakin ingin menghapus pengguna ini?')"
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

                        <!-- Pagination -->
                        @if($users->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                                </div>
                                <div>
                                    {{ $users->links() }}
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Add real-time search if needed
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('filterForm').submit();
                }
            });
        }
    });
</script>
@endpush
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
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 101, 193, 0.2);
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
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: rgba(255, 255, 255, 1);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 101, 193, 0.1);
        }

        /* Search Box */
        .search-box {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 101, 193, 0.2);
            border-radius: 10px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .search-box:focus-within {
            background: rgba(255, 255, 255, 1);
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 101, 193, 0.1);
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 250px;
        }

        .search-box input::placeholder {
            color: var(--light-text);
        }

        /* Table Styling */
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

        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }

        .user-name {
            font-weight: 600;
            color: var(--primary-text);
            margin-bottom: 0.1rem;
        }

        /* Badge Styles */
        .badge-role {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .badge-admin {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: white;
        }

        .badge-dokter {
            background: linear-gradient(135deg, var(--warning-color) 0%, #f59e0b 100%);
            color: white;
        }

        .badge-pasien {
            background: linear-gradient(135deg, var(--success-color) 0%, #10b981 100%);
            color: white;
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
            padding: 3rem 1rem;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }
        
        .empty-text {
            color: var(--light-text);
            font-size: 1.1rem;
        }

        /* Pagination */
        .pagination-custom .page-link {
            border: 1px solid rgba(0, 101, 193, 0.2);
            color: var(--primary-text);
            margin: 0 0.2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .pagination-custom .page-link:hover {
            background: rgba(0, 101, 193, 0.1);
            border-color: var(--secondary-color);
        }

        .pagination-custom .page-item.active .page-link {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
            border-color: var(--secondary-color);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.3rem;
            }

            .search-box input {
                width: 200px;
            }
        }
    </style>
</head>