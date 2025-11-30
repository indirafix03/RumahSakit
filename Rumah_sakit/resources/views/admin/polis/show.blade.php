@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Detail Poli</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.polis.index') }}">Poli</a></li>
                    <li class="breadcrumb-item active">{{ $poli->nama_poli }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.polis.edit', $poli) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('admin.polis.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Poli Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Poli</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nama Poli</th>
                                    <td><strong>{{ $poli->nama_poli }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Total Dokter</th>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $poli->doctors->count() }} Dokter
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Janji Temu</th>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $poli->appointments->count() }} Janji
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Status</th>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle me-1 small"></i>Aktif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>{{ $poli->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diperbarui</th>
                                    <td>{{ $poli->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <h6>Deskripsi Poli</h6>
                        <div class="border rounded p-3 bg-light">
                            {{ $poli->deskripsi ?? 'Tidak ada deskripsi' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Poli Image -->
            @if($poli->has_image && $poli->image_url)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-image me-2"></i>Gambar Poli</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $poli->image_url }}" alt="{{ $poli->nama_poli }}" 
                         class="img-fluid rounded" style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-image me-2"></i>Gambar Poli</h5>
                </div>
                <div class="card-body text-center py-5">
                    <i class="fas fa-hospital fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada gambar untuk poli ini</p>
                </div>
            </div>
            @endif

            <!-- Doctors List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Daftar Dokter</h5>
                </div>
                <div class="card-body">
                    @if($poli->doctors->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Dokter</th>
                                        <th>Email</th>
                                        <th>Jadwal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($poli->doctors as $doctor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2" 
                                                     style="width: 32px; height: 32px; background: linear-gradient(135deg, #0065c1 0%, #009ee5 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                                </div>
                                                <strong>{{ $doctor->name }}</strong>
                                            </div>
                                        </td>
                                        <td><small class="text-muted">{{ $doctor->email }}</small></td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $doctor->schedules->count() }} Jadwal
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $doctor) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-md fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Belum ada dokter di poli ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="stat-box p-3 border rounded">
                                <div class="stat-number fs-3 fw-bold text-primary">{{ $poli->doctors->count() }}</div>
                                <div class="stat-label small text-muted">Dokter</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-box p-3 border rounded">
                                <div class="stat-number fs-3 fw-bold text-success">{{ $poli->appointments->count() }}</div>
                                <div class="stat-label small text-muted">Janji Temu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.polis.edit', $poli) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Poli
                    </a>
                    <form action="{{ route('admin.polis.destroy', $poli) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" 
                                onclick="return confirm('Yakin ingin menghapus poli ini? Tindakan ini tidak dapat dibatalkan.')">
                            <i class="fas fa-trash me-2"></i>Hapus Poli
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
