@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Poli: {{ $poli->nama_poli }}</h1>
        <a href="{{ route('admin.polis.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.polis.update', $poli) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama_poli" class="form-label">Nama Poli <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_poli') is-invalid @enderror" 
                           id="nama_poli" name="nama_poli" value="{{ old('nama_poli', $poli->nama_poli) }}" required>
                    @error('nama_poli')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $poli->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Current image -->
                <div class="mb-3">
                    <label class="form-label">Ikon Saat Ini</label>
                    <div class="border p-2 text-center" style="height: 200px;">
                        @if(!empty($poli->ikon) && \Illuminate\Support\Facades\Storage::disk('public')->exists($poli->ikon))
                            <img id="current-image" src="{{ \Illuminate\Support\Facades\Storage::url($poli->ikon) }}" 
                                 alt="{{ $poli->nama_poli }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                        @else
                            <div class="text-muted">
                                <i class="fas fa-hospital fa-3x mb-2"></i>
                                <p>No Image Available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upload new image -->
                <div class="mb-3">
                    <label for="ikon" class="form-label">Ganti Ikon/Gambar <small class="text-muted">(opsional)</small></label>
                    <input type="file" class="form-control @error('ikon') is-invalid @enderror" 
                           id="ikon" name="ikon" accept="image/*">
                    <div class="form-text">Format: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB. Biarkan kosong jika tidak ingin mengganti.</div>
                    @error('ikon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <!-- Preview container for selected new image -->
                    <div id="new-image-preview-wrapper" class="mt-3" style="display: none;">
                        <label class="form-label">Preview Gambar Baru:</label>
                        <div id="new-image-preview" class="border p-2 text-center" style="height: 200px;"></div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
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
(function() {
    const ikonInput = document.getElementById('ikon');
    const previewWrapper = document.getElementById('new-image-preview-wrapper');
    const previewBox = document.getElementById('new-image-preview');

    ikonInput.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (!file) {
            previewWrapper.style.display = 'none';
            previewBox.innerHTML = '';
            return;
        }

        // show wrapper
        previewWrapper.style.display = 'block';

        const reader = new FileReader();
        reader.onload = function(ev) {
            previewBox.innerHTML = `<img src="${ev.target.result}" class="img-fluid" style="max-height: 100%; object-fit: cover;">`;
        }
        reader.readAsDataURL(file);
    });
})();
</script>
@endsection
