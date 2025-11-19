@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tambah User Baru</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control @error('role') is-invalid @enderror" 
                            id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="admin"  {{ old('role')=='admin' ? 'selected':'' }}>Admin</option>
                        <option value="dokter" {{ old('role')=='dokter' ? 'selected':'' }}>Dokter</option>
                        <option value="pasien" {{ old('role')=='pasien' ? 'selected':'' }}>Pasien</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3" id="poli-field" style="display: none;">
                    <label for="poli_id" class="form-label">Poli</label>
                    <select class="form-control @error('poli_id') is-invalid @enderror" 
                            id="poli_id" name="poli_id" disabled>
                        <option value="">Pilih Poli</option>
                        @foreach($polis as $poli)
                            <option value="{{ $poli->id }}" {{ old('poli_id') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                    @error('poli_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const poliField = document.getElementById('poli-field');
    const poliSelect = document.getElementById('poli_id');

    function togglePoliField() {
        if (roleSelect.value === 'dokter') {
            poliField.style.display = 'block';
            poliSelect.removeAttribute('disabled');
            poliSelect.setAttribute('required', 'required');
        } else {
            poliField.style.display = 'none';
            poliSelect.setAttribute('disabled', 'disabled'); 
            poliSelect.removeAttribute('required');
            poliSelect.value = '';  
        }
    }

    togglePoliField(); // initial load
    roleSelect.addEventListener('change', togglePoliField);
});
</script>
@endsection
