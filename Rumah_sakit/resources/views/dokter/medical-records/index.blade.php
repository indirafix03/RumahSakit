@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Rekam Medis</h1>
        <a href="{{ route('dokter.medical-records.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Rekam Medis
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Pasien</th>
                            <th>Tanggal</th>
                            <th>Diagnosis</th>
                            <th>Tindakan Medis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicalRecords as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $record->pasien->name }}</td>
                            <td>{{ $record->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ Str::limit($record->diagnosis, 50) }}</td>
                            <td>{{ Str::limit($record->tindakan_medis, 50) }}</td>
                            <td>
                                <a href="{{ route('dokter.medical-records.show', $record) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dokter.medical-records.edit', $record) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dokter.medical-records.destroy', $record) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Yakin ingin menghapus rekam medis ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($medicalRecords->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">Belum ada rekam medis</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection