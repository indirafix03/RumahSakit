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
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Obat</th>
                            <th>Tipe</th>
                            <th>Stok</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($medicine->gambar_obat && Storage::disk('public')->exists($medicine->gambar_obat))
                                    <img src="{{ Storage::url($medicine->gambar_obat) }}" 
                                         class="rounded"
                                         style="width:50px;height:50px;object-fit:cover">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                         style="width:50px;height:50px;">
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
                            <td>{{ $medicine->deskripsi ?? '-' }}</td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    <!-- tombol edit -->
                                    <a href="{{ route('admin.medicines.edit', $medicine) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- tombol delete -->
                                    <form action="{{ route('admin.medicines.destroy', $medicine) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Hapus obat {{ $medicine->nama_obat }}?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                Belum ada data obat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
