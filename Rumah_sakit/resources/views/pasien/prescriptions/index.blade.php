@extends('layouts.app')

@section('title', 'Resep Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Resep Saya</h1>
                <div>
                    <span class="badge bg-success me-2">Siap: {{ $readyPrescriptions->count() }}</span>
                    <span class="badge bg-warning me-2">Proses: {{ $pendingPrescriptions->count() }}</span>
                    <span class="badge bg-info">Diambil: {{ $takenPrescriptions->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Resep Siap Diambil -->
    @if($readyPrescriptions->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-check-circle me-2"></i>Resep Siap Diambil
            </h5>
            <span class="badge bg-light text-success">{{ $readyPrescriptions->count() }}</span>
        </div>
        <div class="card-body">
            <div class="alert alert-success">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Resep berikut sudah siap diambil di farmasi!</strong> Silakan datang dengan membawa kartu identitas dan konfirmasi setelah mengambil obat.
            </div>
            
            <div class="row">
                @foreach($readyPrescriptions as $prescription)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-success h-100">
                        <div class="card-header bg-success text-white py-2 d-flex justify-content-between align-items-center">
                            <small>
                                <i class="fas fa-prescription-bottle me-1"></i>
                                Resep #{{ $prescription->id }}
                            </small>
                            <span class="badge bg-light text-success">Siap Diambil</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title text-success mb-3">{{ $prescription->medicine->nama_obat ?? 'N/A' }}</h6>
                            
                            <div class="mb-2">
                                <small class="text-muted d-block">
                                    <strong>Jumlah:</strong> {{ $prescription->quantity }}
                                </small>
                                <small class="text-muted d-block">
                                    <strong>Dokter:</strong> Dr. {{ $prescription->medicalRecord->dokter->name ?? 'N/A' }}
                                </small>
                                <small class="text-muted d-block">
                                    <strong>Tanggal:</strong> {{ $prescription->created_at->format('d/m/Y') }}
                                </small>
                            </div>

                            @if($prescription->instructions)
                            <div class="mb-3 p-2 bg-light rounded">
                                <small>
                                    <strong>Petunjuk:</strong><br>
                                    {{ $prescription->instructions }}
                                </small>
                            </div>
                            @endif

                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-outline-success btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDetail{{ $prescription->id }}">
                                    <i class="fas fa-info-circle me-1"></i> Detail Resep
                                </button>
                                
                                <button class="btn btn-success btn-sm" 
                                        onclick="confirmPickup({{ $prescription->id }})">
                                    <i class="fas fa-check me-1"></i> Konfirmasi Pengambilan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Resep -->
                <div class="modal fade" id="modalDetail{{ $prescription->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-prescription-bottle me-2 text-success"></i>
                                    Detail Resep - {{ $prescription->medicine->nama_obat ?? 'N/A' }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-success">Informasi Obat</h6>
                                        <p><strong>Nama Obat:</strong> {{ $prescription->medicine->nama_obat ?? 'N/A' }}</p>
                                        <p><strong>Jenis:</strong> {{ $prescription->medicine->jenis_obat ?? '-' }}</p>
                                        <p><strong>Dosis:</strong> {{ $prescription->medicine->dosis ?? '-' }}</p>
                                        <p><strong>Jumlah:</strong> {{ $prescription->quantity }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-success">Informasi Resep</h6>
                                        <p><strong>Dokter:</strong> Dr. {{ $prescription->medicalRecord->dokter->name ?? 'N/A' }}</p>
                                        <p><strong>Tanggal Resep:</strong> {{ $prescription->created_at->format('d/m/Y H:i') }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-success">
                                                Siap Diambil
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                @if($prescription->instructions)
                                <div class="mt-4">
                                    <h6 class="text-success">Petunjuk Penggunaan</h6>
                                    <div class="border p-3 rounded bg-light">
                                        <i class="fas fa-info-circle me-2 text-info"></i>
                                        {{ $prescription->instructions }}
                                    </div>
                                </div>
                                @endif
                                
                                <div class="alert alert-success mt-4">
                                    <h6><i class="fas fa-info-circle me-2"></i> Cara Pengambilan Obat:</h6>
                                    <ol class="mb-0 ps-3">
                                        <li>Bawa <strong>kartu identitas (KTP/SIM)</strong> asli</li>
                                        <li>Datang ke <strong>Farmasi Rumah Sakit</strong></li>
                                        <li>Tunjukkan <strong>kode resep: #{{ $prescription->id }}</strong></li>
                                        <li>Tunggu konfirmasi dari petugas farmasi</li>
                                        <li>Setelah mengambil obat, klik tombol <strong class="text-success">"Konfirmasi Pengambilan"</strong></li>
                                    </ol>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-success" onclick="confirmPickup({{ $prescription->id }})">
                                    <i class="fas fa-check me-1"></i> Konfirmasi Pengambilan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Resep Dalam Proses -->
    @if($pendingPrescriptions->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header bg-warning d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-clock me-2"></i>Resep Dalam Proses
            </h5>
            <span class="badge bg-light text-dark">{{ $pendingPrescriptions->count() }}</span>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                Resep berikut sedang diproses oleh farmasi. Anda akan mendapatkan notifikasi ketika status berubah menjadi "Siap Diambil".
            </div>
            
            <div class="row">
                @foreach($pendingPrescriptions as $prescription)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-warning h-100">
                        <div class="card-header bg-warning py-2 d-flex justify-content-between align-items-center">
                            <small>
                                <i class="fas fa-prescription-bottle me-1"></i>
                                Resep #{{ $prescription->id }}
                            </small>
                            <span class="badge bg-light text-dark">Dalam Proses</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title mb-3">{{ $prescription->medicine->nama_obat ?? 'N/A' }}</h6>
                            
                            <div class="mb-2">
                                <small class="text-muted d-block">
                                    <strong>Jumlah:</strong> {{ $prescription->quantity }}
                                </small>
                                <small class="text-muted d-block">
                                    <strong>Dokter:</strong> Dr. {{ $prescription->medicalRecord->dokter->name ?? 'N/A' }}
                                </small>
                                <small class="text-muted d-block">
                                    <strong>Tanggal:</strong> {{ $prescription->created_at->format('d/m/Y') }}
                                </small>
                            </div>

                            @if($prescription->instructions)
                            <div class="mb-3 p-2 bg-light rounded">
                                <small>
                                    <strong>Petunjuk:</strong><br>
                                    {{ Str::limit($prescription->instructions, 100) }}
                                </small>
                            </div>
                            @endif

                            <div class="text-center mt-3">
                                <div class="spinner-border spinner-border-sm text-warning me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <small class="text-warning">Menunggu persiapan obat</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Resep Sudah Diambil -->
    @if($takenPrescriptions->count() > 0)
    <div class="card shadow">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i>Riwayat Resep yang Sudah Diambil
            </h5>
            <span class="badge bg-light text-info">{{ $takenPrescriptions->count() }}</span>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($takenPrescriptions as $prescription)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-info h-100">
                        <div class="card-header bg-info text-white py-2 d-flex justify-content-between align-items-center">
                            <small>
                                <i class="fas fa-prescription-bottle me-1"></i>
                                Resep #{{ $prescription->id }}
                            </small>
                            <span class="badge bg-light text-info">Sudah Diambil</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title mb-3">{{ $prescription->medicine->nama_obat ?? 'N/A' }}</h6>
                            
                            <div class="mb-2">
                                <small class="text-muted d-block">
                                    <strong>Jumlah:</strong> {{ $prescription->quantity }}
                                </small>
                                <small class="text-muted d-block">
                                    <strong>Dokter:</strong> Dr. {{ $prescription->medicalRecord->dokter->name ?? 'N/A' }}
                                </small>
                                <small class="text-muted d-block">
                                    <strong>Diambil pada:</strong> {{ $prescription->updated_at->format('d/m/Y H:i') }}
                                </small>
                            </div>

                            @if($prescription->instructions)
                            <div class="mb-3 p-2 bg-light rounded">
                                <small>
                                    <strong>Petunjuk:</strong><br>
                                    {{ Str::limit($prescription->instructions, 100) }}
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($readyPrescriptions->count() == 0 && $pendingPrescriptions->count() == 0 && $takenPrescriptions->count() == 0)
    <div class="text-center py-5">
        <i class="fas fa-prescription-bottle fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">Belum Ada Resep</h4>
        <p class="text-muted">Resep dari dokter akan muncul di sini setelah konsultasi.</p>
        <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-calendar-plus me-1"></i> Buat Janji Temu
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function confirmPickup(prescriptionId) {
    if (confirm('Apakah Anda sudah mengambil obat ini dari farmasi?\n\nPastikan Anda sudah menerima obat yang sesuai dengan resep.')) {
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
        button.disabled = true;

        // Send request to confirm pickup
        fetch(`/pasien/prescriptions/${prescriptionId}/confirm-pickup`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and reload
                alert('✅ Berhasil! Resep telah dikonfirmasi sebagai sudah diambil.');
                location.reload();
            } else {
                alert('❌ ' + (data.message || 'Gagal mengkonfirmasi pengambilan'));
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat mengkonfirmasi pengambilan');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

// Auto-close alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush