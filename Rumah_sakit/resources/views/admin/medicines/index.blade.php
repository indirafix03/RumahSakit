@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Medicine Management</h1>
        <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Obat
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 70px;">Gambar</th>
                            <th>Nama Obat</th>
                            <th>Tipe Obat</th>
                            <th>Stok</th>
                            <th>Deskripsi</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if(!empty($medicine->gambar_obat) && \Illuminate\Support\Facades\Storage::disk('public')->exists($medicine->gambar_obat))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($medicine->gambar_obat) }}" 
                                         alt="{{ $medicine->nama_obat }}" 
                                         style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                         style="width:50px; height:50px;">
                                        <i class="fas fa-pills text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $medicine->nama_obat }}</td>
                            <td>
                                <span class="badge bg-{{ $medicine->tipe_obat === 'keras' ? 'danger' : 'success' }}">
                                    {{ ucfirst($medicine->tipe_obat) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $medicine->stok > 10 ? 'success' : ($medicine->stok > 0 ? 'warning' : 'danger') }}">
                                    {{ $medicine->stok }}
                                </span>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($medicine->deskripsi ?? '-', 50) }}</td>
                            <td class="text-nowrap">
                            <td class="text-nowrap">
                                <div class="dropdown">
                                    <ul class="dropdown-menu" aria-labelledby="actionsDropdown{{ $medicine->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.medicines.edit', $medicine) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.medicines.destroy', $medicine) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ addslashes($medicine->nama_obat) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">
                                                    <i class="fas fa-trash me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data obat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
