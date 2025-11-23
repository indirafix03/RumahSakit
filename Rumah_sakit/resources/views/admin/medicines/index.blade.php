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

    <!-- Filter dan Search Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.medicines.index') }}" method="GET">
                <div class="row g-3">
                    <!-- Search Input -->
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari Obat</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               placeholder="Cari berdasarkan nama atau deskripsi..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Status Filter - DIPERBAIKI -->
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Stok</label>
                        <select class="form-select" id="status" name="status">
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

                    <!-- Type Filter -->
                    <div class="col-md-3">
                        <label for="type" class="form-label">Tipe Obat</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Semua Tipe</option>
                            <option value="keras" {{ request('type') == 'keras' ? 'selected' : '' }}>
                                Keras
                            </option>
                            <option value="biasa" {{ request('type') == 'biasa' ? 'selected' : '' }}>
                                Biasa
                            </option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            @if(request()->hasAny(['search', 'status', 'type']))
                            <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Reset
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
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-info-circle me-2"></i>
            Menampilkan hasil filter
            @if(request('search'))
                - Pencarian: "{{ request('search') }}"
            @endif
            @if(request('status'))
                - Status: 
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
                - Tipe: {{ ucfirst(request('type')) }}
            @endif
        </div>
        <small>Total: {{ $medicines->count() }} obat</small>
    </div>
    @endif

    <!-- Medicines Table -->
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
                            <th>Status Stok</th>
                            <th>Tanggal Kadaluarsa</th>
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
                                         style="width:50px;height:50px;object-fit:cover"
                                         alt="{{ $medicine->nama_obat }}">
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
                                <span class="badge bg-{{ $medicine->stok > 10 ? 'success' : ($medicine->stok > 0 ? 'warning' : 'secondary') }}">
                                    {{ $medicine->stok }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusInfo = $medicine->getStatusInfo();
                                @endphp
                                <span class="badge bg-{{ $statusInfo['color'] }}" title="{{ $statusInfo['reason'] }}">
                                    {{ $statusInfo['label'] }}
                                </span>
                            </td>
                            <td>
                                {!! $medicine->getFormattedExpiredDate() !!}
                            </td>
                            <td>{{ Str::limit($medicine->deskripsi, 50) }}</td>
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
                            <td colspan="9" class="text-center text-muted py-3">
                                @if(request()->hasAny(['search', 'status', 'type']))
                                    Tidak ada obat yang sesuai dengan filter yang dipilih.
                                @else
                                    Belum ada data obat.
                                @endif
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