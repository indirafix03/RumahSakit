@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Poli Management</h1>
        <a href="{{ route('admin.polis.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Poli
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @foreach($polis as $poli)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                    @if($poli->has_image && $poli->image_url)
                        <img src="{{ $poli->image_url }}" 
                            alt="{{ $poli->nama_poli }}"
                            class="img-fluid"
                            style="max-height: 100%; max-width: 100%; object-fit: cover;"
                            onerror="this.src='https://via.placeholder.com/300x200/EFEFEF/666666?text=Image+Error'">
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-hospital fa-3x mb-2"></i>
                            <p>No Image Available</p>
                            @if(!empty($poli->ikon))
                                <small class="text-danger">Path: {{ $poli->ikon }}</small>
                            @else
                                <small class="text-danger">Ikon field is empty</small>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $poli->nama_poli }}</h5>
                    <p class="card-text">{{ \Illuminate\Support\Str::limit($poli->deskripsi, 100) }}</p>
                    @if(!empty($poli->ikon))
                        <small class="text-muted">Path: {{ $poli->ikon }}</small>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.polis.edit', $poli) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.polis.destroy', $poli) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus poli {{ $poli->nama_poli }}?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection