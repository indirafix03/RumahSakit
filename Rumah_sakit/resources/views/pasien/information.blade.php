@extends('layouts.app')

@section('title', 'Informasi Layanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4">Informasi Layanan Rumah Sakit</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                    <h5>Jam Operasional</h5>
                    <p class="mb-1"><strong>Senin - Jumat:</strong> 07:00 - 21:00</p>
                    <p class="mb-1"><strong>Sabtu:</strong> 07:00 - 17:00</p>
                    <p class="mb-0"><strong>Minggu:</strong> 08:00 - 15:00</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-phone fa-3x text-success mb-3"></i>
                    <h5>Kontak Darurat</h5>
                    <p class="mb-1"><strong>UGD:</strong> (021) 1234-5678</p>
                    <p class="mb-1"><strong>Informasi:</strong> (021) 1234-5679</p>
                    <p class="mb-0"><strong>Ambulans:</strong> 119</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <i class="fas fa-map-marker-alt fa-3x text-danger mb-3"></i>
                    <h5>Lokasi</h5>
                    <p class="mb-0">Jl. Kesehatan No. 123<br>Jakarta Pusat 10110</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Poli & Layanan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($polis as $poli)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                @if($poli->ikon)
                                    <i class="{{ $poli->ikon }} fa-2x text-primary me-3"></i>
                                @else
                                    <i class="fas fa-stethoscope fa-2x text-primary me-3"></i>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $poli->nama_poli }}</h6>
                                    <p class="text-muted mb-0 small">{{ $poli->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection