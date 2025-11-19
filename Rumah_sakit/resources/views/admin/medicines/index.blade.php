@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Medicine Management</h1>
        <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Obat
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Nama Obat</th>
                            <th>Tipe Obat</th>
                            <th>Stok</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicines as $medicine)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ Storage::url($medicine->gambar) }}" alt="{{ $medicine->nama_obat }}" 
                                     style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
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
                            <td>{{ Str::limit($medicine->deskripsi, 50) }}</td>
                            <td>
                                <a href="{{ route('admin.medicines.edit', $medicine) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.medicines.destroy', $medicine) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection