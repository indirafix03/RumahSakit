@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Obat</h1>
        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.medicines.update', $medicine) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nama Obat --}}
                <div class="mb-3">
                    <label class="form-label">Nama Obat</label>
                    <input type="text" name="nama_obat" class="form-control"
                           value="{{ old('nama_obat', $medicine->nama_obat) }}" required>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $medicine->deskripsi) }}</textarea>
                </div>

                {{-- Tipe --}}
                <div class="mb-3">
                    <label class="form-label">Tipe Obat</label>
                    <select name="tipe_obat" class="form-select" required>
                        <option value="keras" {{ $medicine->tipe_obat == 'keras' ? 'selected' : '' }}>Keras</option>
                        <option value="biasa" {{ $medicine->tipe_obat == 'biasa' ? 'selected' : '' }}>Biasa</option>
                    </select>
                </div>

                {{-- Stok --}}
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" min="0" name="stok" class="form-control"
                           value="{{ old('stok', $medicine->stok) }}" required>
                </div>

                {{-- bagian Gambar lama --}}
                <div class="mb-3">
                    <label class="form-label">Gambar Saat Ini</label>
                    <div class="border p-2 text-center" style="height:200px;">
                        @if(!empty($medicine->gambar_obat) && \Illuminate\Support\Facades\Storage::disk('public')->exists($medicine->gambar_obat))
                            <img id="current-image" src="{{ \Illuminate\Support\Facades\Storage::url($medicine->gambar_obat) }}"
                                class="img-fluid" style="max-height:100%; object-fit:cover;">
                        @else
                            <div class="text-muted">
                                <i class="fas fa-pills fa-3x mb-2"></i>
                                <p>No Image Available</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Upload baru --}}
                <div class="mb-3">
                    <label class="form-label">Ganti Gambar (opsional)</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti.</small>

                    <div id="new-preview-wrapper" class="mt-3" style="display:none;">
                        <label class="form-label">Preview Gambar Baru</label>
                        <div class="border p-2 text-center" style="height:200px;">
                            <img id="new-preview-image" class="img-fluid" style="max-height:100%; object-fit:cover;">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form> 
        </div>
    </div>
</div>
<script>
(function(){
    const input = document.getElementById('gambar');
    const wrapper = document.getElementById('new-preview-wrapper');
    const img = document.getElementById('new-preview-image');

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) {
            wrapper.style.display = 'none';
            img.src = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(ev) {
            img.src = ev.target.result;
            wrapper.style.display = 'block';
        }
        reader.readAsDataURL(file);
    });
})();
</script>
@endsection
