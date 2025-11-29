@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Page Content -->
    <main>
        <!-- Page Header -->
        <div class="page-header">
            <div class="container position-relative">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="page-title">Edit Poli</h1>
                        <p class="page-subtitle mb-0">Update informasi poli {{ $poli->nama_poli }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('admin.polis.index') }}" class="btn btn-secondary-custom">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Poli
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-edit me-2"></i>
                        Form Edit Poli
                        <span class="section-badge">{{ $poli->nama_poli }}</span>
                    </h2>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.polis.update', $poli) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-custom mb-4">
                                    <label for="nama_poli" class="form-label-custom">
                                        <i class="fas fa-tag me-2"></i>Nama Poli
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control-custom @error('nama_poli') is-invalid-custom @enderror" 
                                           id="nama_poli" name="nama_poli" value="{{ old('nama_poli', $poli->nama_poli) }}" 
                                           placeholder="Masukkan nama poli" required>
                                    @error('nama_poli')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-custom mb-4">
                                    <label for="deskripsi" class="form-label-custom">
                                        <i class="fas fa-align-left me-2"></i>Deskripsi Poli
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control-custom @error('deskripsi') is-invalid-custom @enderror" 
                                              id="deskripsi" name="deskripsi" rows="4" 
                                              placeholder="Deskripsi lengkap tentang layanan poli..." required>{{ old('deskripsi', $poli->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                    <div class="form-help-text">
                                        Jelaskan secara detail tentang layanan, fasilitas, dan spesialisasi poli ini
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Image Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="fas fa-image me-2"></i>Ikon Saat Ini
                                    </label>
                                    <div class="current-image-container">
                                        @if(!empty($poli->ikon) && \Illuminate\Support\Facades\Storage::disk('public')->exists($poli->ikon))
                                            <img id="current-image" src="{{ \Illuminate\Support\Facades\Storage::url($poli->ikon) }}" 
                                                 alt="{{ $poli->nama_poli }}" class="current-image">
                                            <div class="current-image-overlay">
                                                <span class="current-image-badge">Gambar Saat Ini</span>
                                            </div>
                                        @else
                                            <div class="current-image-placeholder">
                                                <i class="fas fa-hospital fa-3x placeholder-icon"></i>
                                                <p class="placeholder-text">No Image Available</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- New Image Upload Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-custom mb-4">
                                    <label for="ikon" class="form-label-custom">
                                        <i class="fas fa-sync-alt me-2"></i>Ganti Ikon/Gambar
                                        <small class="text-muted">(opsional)</small>
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" class="file-upload-input @error('ikon') is-invalid-custom @enderror" 
                                               id="ikon" name="ikon" accept="image/*">
                                        <div class="file-upload-area" id="file-upload-area">
                                            <div class="file-upload-content">
                                                <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                                                <p class="file-upload-text">Klik untuk memilih gambar baru atau drag & drop di sini</p>
                                                <p class="file-upload-subtext">Format: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB. Biarkan kosong jika tidak ingin mengganti.</p>
                                            </div>
                                        </div>
                                    </div>
                                    @error('ikon')
                                        <div class="invalid-feedback-custom">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- New Image Preview -->
                        <div class="row" id="new-image-preview-wrapper" style="display: none;">
                            <div class="col-md-12">
                                <div class="image-preview-container">
                                    <label class="form-label-custom">
                                        <i class="fas fa-eye me-2"></i>Preview Gambar Baru
                                    </label>
                                    <div class="image-preview" id="new-image-preview">
                                        <!-- Preview image baru akan muncul di sini -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('admin.polis.index') }}" class="btn btn-secondary-custom">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
:root {
    --primary-text: #094fa4;
    --secondary-color: #0065c1;
    --card-color: #009ee5;
    --background-color: #52bcec;
    --light-bg: #f8f9fa;
    --dark-text: #2c3e50;
    --light-text: #7f8c8d;
    --white: #ffffff;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #3b82f6;
}

body {
    font-family: 'Oswald', sans-serif;
    color: var(--primary-text);
    line-height: 1.6;
    font-weight: 400;
    background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
    min-height: 100vh;
}

.admin-navbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 1rem 0;
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.8rem;
    background: linear-gradient(135deg, var(--primary-text) 0%, var(--secondary-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 0.5px;
}

.nav-link {
    font-weight: 500;
    color: var(--primary-text);
    margin: 0 0.5rem;
    letter-spacing: 0.5px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    transition: width 0.3s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 100%;
}

.nav-link.active {
    color: var(--secondary-color);
    font-weight: 600;
}

.btn-logout {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    color: var(--white);
    border: none;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
}

.page-header {
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    color: var(--white);
    padding: 2rem 0;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,50 1000,100 0,100"/></svg>');
    background-size: cover;
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    position: relative;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 300;
}

/* Section Cards */
.section-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 
        0 8px 32px rgba(0, 101, 193, 0.1),
        0 2px 8px rgba(0, 101, 193, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.section-card:hover {
    box-shadow: 
        0 12px 40px rgba(0, 101, 193, 0.15),
        0 4px 12px rgba(0, 101, 193, 0.1);
}

.section-header {
    background: linear-gradient(135deg, rgba(0, 101, 193, 0.05) 0%, rgba(0, 158, 229, 0.05) 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    color: var(--primary-text);
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
}

.section-badge {
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    color: var(--white);
    padding: 0.4rem 1rem;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.8rem;
    margin-left: 0.8rem;
}

.section-body {
    padding: 2rem;
}

/* Button Styles */
.btn-primary-custom {
    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
    color: var(--white);
    border: none;
    padding: 0.8rem 2rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 101, 193, 0.2);
    text-transform: uppercase;
    font-size: 0.9rem;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 101, 193, 0.3);
    color: var(--white);
}

.btn-secondary-custom {
    background: rgba(255, 255, 255, 0.9);
    color: var(--primary-text);
    border: 1px solid rgba(0, 101, 193, 0.2);
    padding: 0.8rem 2rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 12px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    font-size: 0.9rem;
}

.btn-secondary-custom:hover {
    background: rgba(255, 255, 255, 1);
    border-color: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 101, 193, 0.1);
    color: var(--primary-text);
}

/* Form Styles */
.form-group-custom {
    position: relative;
}

.form-label-custom {
    font-weight: 600;
    color: var(--primary-text);
    margin-bottom: 0.5rem;
    display: block;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
    text-transform: uppercase;
}

.form-label-custom .text-danger {
    color: var(--danger-color) !important;
}

.form-control-custom {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 101, 193, 0.2);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
    font-family: 'Oswald', sans-serif;
}

.form-control-custom:focus {
    background: rgba(255, 255, 255, 1);
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(0, 101, 193, 0.1);
    outline: none;
}

.form-control-custom::placeholder {
    color: var(--light-text);
    opacity: 0.7;
}

/* Textarea specific */
.form-control-custom textarea {
    resize: vertical;
    min-height: 100px;
}

/* Invalid State */
.is-invalid-custom {
    border-color: var(--danger-color);
    background: rgba(239, 68, 68, 0.05);
}

.is-invalid-custom:focus {
    border-color: var(--danger-color);
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.invalid-feedback-custom {
    color: var(--danger-color);
    font-size: 0.85rem;
    margin-top: 0.25rem;
    font-weight: 500;
}

/* Form Help Text */
.form-help-text {
    font-size: 0.8rem;
    color: var(--light-text);
    margin-top: 0.25rem;
    font-style: italic;
}

/* Current Image Styles */
.current-image-container {
    position: relative;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 101, 193, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.current-image {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.current-image-overlay {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.current-image-badge {
    background: linear-gradient(135deg, var(--warning-color) 0%, #f59e0b 100%);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.current-image-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--light-text);
    padding: 2rem;
}

.placeholder-icon {
    margin-bottom: 1rem;
    opacity: 0.5;
}

.placeholder-text {
    margin-bottom: 0;
    font-weight: 500;
}

/* File Upload Styles */
.file-upload-wrapper {
    position: relative;
}

.file-upload-input {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    cursor: pointer;
    opacity: 0;
}

.file-upload-area {
    background: rgba(255, 255, 255, 0.8);
    border: 2px dashed rgba(0, 101, 193, 0.3);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-area:hover {
    background: rgba(255, 255, 255, 1);
    border-color: var(--secondary-color);
}

.file-upload-area.dragover {
    background: rgba(0, 101, 193, 0.05);
    border-color: var(--secondary-color);
    border-style: solid;
}

.file-upload-icon {
    font-size: 2.5rem;
    color: var(--secondary-color);
    margin-bottom: 1rem;
    opacity: 0.7;
}

.file-upload-text {
    font-weight: 600;
    color: var(--primary-text);
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.file-upload-subtext {
    color: var(--light-text);
    font-size: 0.85rem;
    margin-bottom: 0;
}

/* Image Preview */
.image-preview-container {
    margin-top: 1rem;
}

.image-preview {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 101, 193, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-preview img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.3rem;
    }
    
    .btn-primary-custom,
    .btn-secondary-custom {
        padding: 0.7rem 1.5rem;
        font-size: 0.85rem;
    }
    
    .section-body {
        padding: 1.5rem;
    }
    
    .file-upload-area {
        padding: 1.5rem;
    }
    
    .file-upload-icon {
        font-size: 2rem;
    }
    
    .current-image-container,
    .image-preview {
        padding: 1rem;
        min-height: 150px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('ikon');
    const fileUploadArea = document.getElementById('file-upload-area');
    const newImagePreviewWrapper = document.getElementById('new-image-preview-wrapper');
    const newImagePreview = document.getElementById('new-image-preview');

    // Drag and drop functionality
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadArea.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect(e.dataTransfer.files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            handleFileSelect(this.files[0]);
        } else {
            resetFileUpload();
        }
    });

    // Handle file selection
    function handleFileSelect(file) {
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                newImagePreview.innerHTML = `<img src="${ev.target.result}" class="img-fluid" alt="Preview">`;
                newImagePreviewWrapper.style.display = 'block';
                
                // Update upload area text
                const uploadContent = fileUploadArea.querySelector('.file-upload-content');
                uploadContent.innerHTML = `
                    <i class="fas fa-check-circle file-upload-icon text-success"></i>
                    <p class="file-upload-text">File selected: ${file.name}</p>
                    <p class="file-upload-subtext">Size: ${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                `;
            }
            reader.readAsDataURL(file);
        }
    }

    // Reset file upload area
    function resetFileUpload() {
        newImagePreviewWrapper.style.display = 'none';
        newImagePreview.innerHTML = '';
        
        const uploadContent = fileUploadArea.querySelector('.file-upload-content');
        uploadContent.innerHTML = `
            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
            <p class="file-upload-text">Klik untuk memilih gambar baru atau drag & drop di sini</p>
            <p class="file-upload-subtext">Format: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB. Biarkan kosong jika tidak ingin mengganti.</p>
        `;
    }

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endsection