@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="page-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Manajemen Poli</h1>
                <p class="page-subtitle">Kelola data poli dan layanan kesehatan</p>
            </div>
            <div class="text-white">
                <p class="mb-0 fw-medium">
                    <i class="fas fa-clinic-medical me-2"></i>
                    Total: {{ $polis->count() }} Poli
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <strong>Sukses!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Action Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="text-primary mb-0">
                <i class="fas fa-list me-2"></i>Daftar Poli
            </h5>
        </div>
        <a href="{{ route('admin.polis.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Poli
        </a>
    </div>

    <!-- Poli Cards Grid -->
    <div class="row">
        @foreach($polis as $poli)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card poli-card h-100 border-0 shadow-sm">
                <div class="poli-image-container position-relative">
                    @if($poli->has_image && $poli->image_url)
                        <div class="image-wrapper" style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ $poli->image_url }}" 
                                alt="{{ $poli->nama_poli }}"
                                class="poli-image img-fluid"
                                style="width: 100%; height: 100%; object-fit: contain; background: #f8f9fa;"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="poli-image-placeholder d-none flex-column justify-content-center align-items-center bg-light w-100 h-100">
                                <i class="fas fa-hospital fa-3x text-muted mb-2"></i>
                                <p class="mt-2 mb-0 text-muted">Gagal memuat gambar</p>
                            </div>
                        </div>
                    @else
                        <div class="poli-image-placeholder d-flex flex-column justify-content-center align-items-center bg-light"
                             style="height: 200px;">
                            <i class="fas fa-hospital fa-3x text-muted mb-2"></i>
                            <p class="mt-2 mb-0 text-muted">No Image</p>
                            @if(!empty($poli->ikon))
                                <small class="text-muted">Path: {{ $poli->ikon }}</small>
                            @else
                                <small class="text-muted">Ikon belum diupload</small>
                            @endif
                        </div>
                    @endif
                    <div class="poli-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                         style="background: rgba(0,0,0,0.7); opacity: 0; transition: opacity 0.3s;">
                        <div class="poli-actions">
                            <a href="{{ route('admin.polis.edit', $poli) }}" class="btn btn-warning btn-sm me-2" title="Edit Poli">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="confirmDelete('{{ $poli->nama_poli }}', '{{ route('admin.polis.destroy', $poli) }}')"
                                    title="Hapus Poli">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body poli-content">
                    <h5 class="poli-title card-title text-primary">{{ $poli->nama_poli }}</h5>
                    <p class="poli-description card-text text-muted">{{ \Illuminate\Support\Str::limit($poli->deskripsi, 120) }}</p>
                    
                    <div class="poli-meta d-flex justify-content-between mb-3">
                        <div class="meta-item d-flex align-items-center">
                            <i class="fas fa-user-md text-primary me-2"></i>
                            <span class="small">{{ $poli->doctors_count }} Dokter</span>
                        </div>
                        <div class="meta-item d-flex align-items-center">
                            <i class="fas fa-calendar-check text-success me-2"></i>
                            <span class="small">{{ $poli->appointments_count }} Janji</span>
                        </div>
                    </div>

                    <div class="poli-footer mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="poli-status status-active badge bg-success">
                                <i class="fas fa-circle me-1 small"></i>
                                Aktif
                            </span>
                            <div class="poli-actions-footer">
                                <a href="{{ route('admin.polis.show', $poli) }}" class="btn btn-outline-info btn-sm me-1" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.polis.edit', $poli) }}" class="btn btn-outline-primary btn-sm me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                        onclick="confirmDelete('{{ $poli->nama_poli }}', '{{ route('admin.polis.destroy', $poli) }}')"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @if($polis->isEmpty())
        <div class="col-12">
            <div class="card empty-state border-0 text-center py-5">
                <div class="card-body">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-clinic-medical fa-4x text-muted"></i>
                    </div>
                    <h5 class="empty-state-title text-muted mb-3">Belum ada poli</h5>
                    <p class="empty-state-text text-muted mb-4">Mulai dengan menambahkan poli pertama Anda.</p>
                    <div class="mt-3">
                        <a href="{{ route('admin.polis.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Tambah Poli Pertama
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus poli <strong id="poliName"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Custom Styles for Poli Management */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.poli-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.poli-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.poli-image-container:hover .poli-overlay {
    opacity: 1 !important;
}

.image-wrapper {
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.poli-image {
    transition: transform 0.3s ease;
    border-radius: 8px 8px 0 0;
}

.poli-card:hover .poli-image {
    transform: scale(1.05);
}

.poli-image-placeholder {
    border-radius: 8px 8px 0 0;
}

.poli-title {
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.poli-description {
    line-height: 1.5;
    color: #6c757d;
}

.poli-meta {
    font-size: 0.9rem;
}

.meta-item {
    display: flex;
    align-items: center;
}

.poli-footer {
    border-top: 1px solid #e9ecef;
}

.poli-actions-footer .btn {
    padding: 0.25rem 0.5rem;
}

.empty-state {
    background: #f8f9fa;
    border-radius: 12px;
}

.empty-state-icon {
    opacity: 0.7;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem 0;
    }
    
    .poli-card {
        margin-bottom: 1rem;
    }
}

/* Image size options - choose one */
/* Option 1: Contain (menjaga aspect ratio tanpa crop) */
.poli-image.contain {
    object-fit: contain !important;
}

/* Option 2: Scale down (mengecilkan gambar besar, tidak memperbesar gambar kecil) */
.poli-image.scale-down {
    object-fit: scale-down !important;
}

/* Option 3: Custom responsive dengan max dimensions */
.poli-image.responsive {
    max-width: 100%;
    max-height: 200px;
    width: auto;
    height: auto;
}

/* Option 4: Background image approach */
.poli-image-bg {
    height: 200px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    background-color: #f8f9fa;
    border-radius: 8px 8px 0 0;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(poliName, deleteUrl) {
    document.getElementById('poliName').textContent = poliName;
    document.getElementById('deleteForm').action = deleteUrl;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Hover effect for poli cards
document.addEventListener('DOMContentLoaded', function() {
    const poliCards = document.querySelectorAll('.poli-card');
    
    poliCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Preload images and handle aspect ratio
    const images = document.querySelectorAll('.poli-image');
    images.forEach(img => {
        img.addEventListener('load', function() {
            // Gambar berhasil dimuat
            this.style.opacity = '1';
        });
        
        img.addEventListener('error', function() {
            // Fallback ke placeholder
            this.style.display = 'none';
            const placeholder = this.nextElementSibling;
            if (placeholder && placeholder.classList.contains('poli-image-placeholder')) {
                placeholder.classList.remove('d-none');
                placeholder.classList.add('d-flex');
            }
        });
    });
});
</script>
@endpush