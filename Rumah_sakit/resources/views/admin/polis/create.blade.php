@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tambah Poli Baru</h1>
        <a href="{{ route('admin.polis.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.polis.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="nama_poli" class="form-label">Nama Poli <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_poli') is-invalid @enderror" 
                           id="nama_poli" name="nama_poli" value="{{ old('nama_poli') }}" required>
                    @error('nama_poli')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="ikon" class="form-label">Ikon/Gambar <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('ikon') is-invalid @enderror" 
                           id="ikon" name="ikon" accept="image/*" required>
                    <div class="form-text">Format: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB.</div>
                    @error('ikon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.polis.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview image sebelum upload
<script>
document.getElementById('ikon').addEventListener('change', function(e) {
    const file = e.target.files[0];
        // cari container preview, buat jika belum ada
    let previewWrapper = document.getElementById('image-preview-wrapper');
        if (!previewWrapper) {
            previewWrapper = document.createElement('div');
            previewWrapper.id = 'image-preview-wrapper';
            previewWrapper.className = 'mt-3';
            // tempat untuk menaruh gambar
            previewWrapper.innerHTML = '<label class="form-label">Preview:</label><div id="image-preview" class="border p-2 text-center"></div>';
            this.parentNode.appendChild(previewWrapper);
        }

        const preview = document.getElementById('image-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.innerHTML = `<img src="${ev.target.result}" class="img-fluid" style="max-height: 200px;">`;
            }
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '<p class="text-muted">No image selected</p>';
        }
    });
</script>
@endsection