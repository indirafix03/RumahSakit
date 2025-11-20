@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tambah Obat</h1>
        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.medicines.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nama Obat -->
                <div class="mb-3">
                    <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                    <input type="text" name="nama_obat" class="form-control @error('nama_obat') is-invalid @enderror"
                           value="{{ old('nama_obat') }}" required>
                    @error('nama_obat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipe Obat -->
                <div class="mb-3">
                    <label class="form-label">Tipe Obat <span class="text-danger">*</span></label>
                    <select name="tipe_obat" class="form-control @error('tipe_obat') is-invalid @enderror" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="biasa" {{ old('tipe_obat') === 'biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="keras" {{ old('tipe_obat') === 'keras' ? 'selected' : '' }}>Keras</option>
                    </select>
                    @error('tipe_obat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stok -->
                <div class="mb-3">
                    <label class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" min="0" name="stok"
                           class="form-control @error('stok') is-invalid @enderror"
                           value="{{ old('stok') }}" required>
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" rows="3"
                              class="form-control @error('deskripsi') is-invalid @enderror"
                              required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Gambar -->
                <div class="mb-3">
                    <label class="form-label">Gambar Obat <span class="text-danger">*</span></label>
                    <input type="file" name="gambar" id="gambar" accept="image/*"
                        class="form-control @error('gambar') is-invalid @enderror" required>
                    <div class="form-text">Format: JPG, PNG. Maks 2MB.</div>
                    @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    <div id="preview-container" class="mt-3" style="display:none;">
                        <label class="form-label">Preview:</label>
                        <div class="border p-2 text-center">
                            <img id="preview-image" src="" class="img-fluid" style="max-height:200px; object-fit:cover;">
                        </div>
                    </div>
                </div>
                <!-- Button -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
document.getElementById('gambar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');

    if (!file) {
        previewContainer.style.display = 'none';
        previewImage.src = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(ev) {
        previewImage.src = ev.target.result;
        previewContainer.style.display = 'block';
    }
    reader.readAsDataURL(file);
});
</script>
@endsection
