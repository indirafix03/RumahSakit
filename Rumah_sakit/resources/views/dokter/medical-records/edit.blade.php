@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Rekam Medis</h1>
        <a href="{{ route('dokter.medical-records.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Rekam Medis
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Form Edit Rekam Medis</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dokter.medical-records.update', $medicalRecord->id) }}" method="POST" id="medicalRecordForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Info Pasien (Readonly) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Informasi Pasien</label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Nama Pasien:</strong> 
                                            {{ $medicalRecord->appointment->pasien->name ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Tanggal Janji Temu:</strong> 
                                            {{ $medicalRecord->appointment->tanggal_booking?->format('d/m/Y') ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Diagnosis -->
                        <div class="mb-4">
                            <label for="diagnosis" class="form-label fw-bold">Diagnosis <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('diagnosis') is-invalid @enderror" 
                                      id="diagnosis" name="diagnosis" rows="4" 
                                      placeholder="Masukkan diagnosis utama pasien..." required>{{ old('diagnosis', $medicalRecord->diagnosis) }}</textarea>
                            @error('diagnosis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Diagnosis utama berdasarkan pemeriksaan</div>
                        </div>

                        <!-- Tindakan Medis -->
                        <div class="mb-4">
                            <label for="tindakan_medis" class="form-label fw-bold">Tindakan Medis <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('tindakan_medis') is-invalid @enderror" 
                                      id="tindakan_medis" name="tindakan_medis" rows="4"
                                      placeholder="Jelaskan tindakan medis yang dilakukan..." required>{{ old('tindakan_medis', $medicalRecord->tindakan_medis) }}</textarea>
                            @error('tindakan_medis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tindakan yang dilakukan selama konsultasi</div>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="mb-4">
                            <label for="catatan" class="form-label fw-bold">Catatan Tambahan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="3"
                                      placeholder="Catatan tambahan (opsional)...">{{ old('catatan', $medicalRecord->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Catatan lain yang perlu dicatat</div>
                        </div>

                        <!-- Resep Obat -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold">Resep Obat <span class="text-danger">*</span></label>
                                <button type="button" id="add-medicine" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-plus me-1"></i>Tambah Obat
                                </button>
                            </div>

                            <div id="prescription-items">
                                @php
                                    $existingPrescriptions = $medicalRecord->prescriptions ?? [];
                                @endphp
                                
                                @if(count($existingPrescriptions) > 0)
                                    <!-- Tampilkan resep yang sudah ada -->
                                    @foreach($existingPrescriptions as $index => $prescription)
                                    <div class="prescription-item card mb-3">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Pilih Obat <span class="text-danger">*</span></label>
                                                    <select class="form-select medicine-select" name="obat_id[]" required>
                                                        <option value="">-- Pilih Obat --</option>
                                                        @foreach($medicines as $medicine)
                                                            <option value="{{ $medicine->id }}" 
                                                                data-stok="{{ $medicine->stok }}"
                                                                {{ $prescription->obat_id == $medicine->id ? 'selected' : '' }}>
                                                                {{ $medicine->nama_obat }} ({{ $medicine->tipe_obat }}) - Stok: {{ $medicine->stok }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control quantity-input" 
                                                           name="jumlah[]" min="1" max="100" 
                                                           value="{{ $prescription->jumlah }}" 
                                                           placeholder="Jumlah" required>
                                                    <div class="form-text stok-info text-muted small"></div>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-item w-100" 
                                                            {{ $loop->first ? 'style="display: none;"' : '' }}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <!-- Item Obat Default -->
                                    <div class="prescription-item card mb-3">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Pilih Obat <span class="text-danger">*</span></label>
                                                    <select class="form-select medicine-select" name="obat_id[]" required>
                                                        <option value="">-- Pilih Obat --</option>
                                                        @foreach($medicines as $medicine)
                                                            <option value="{{ $medicine->id }}" data-stok="{{ $medicine->stok }}">
                                                                {{ $medicine->nama_obat }} ({{ $medicine->tipe_obat }}) - Stok: {{ $medicine->stok }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control quantity-input" 
                                                           name="jumlah[]" min="1" max="100" placeholder="Jumlah" required>
                                                    <div class="form-text stok-info text-muted small"></div>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-item w-100" style="display: none;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($medicines->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Tidak ada obat yang tersedia di sistem.
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('dokter.medical-records.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary" 
                                    {{ $medicines->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-save me-1"></i> Update Rekam Medis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Info Rekam Medis -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Rekam Medis</h6>
                </div>
                <div class="card-body">
                    <div class="text-start">
                        <p class="mb-2"><strong>Dibuat:</strong> {{ $medicalRecord->created_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-2"><strong>Diupdate:</strong> {{ $medicalRecord->updated_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-0"><strong>Dokter:</strong> {{ $medicalRecord->dokter->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Panduan -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Panduan Edit</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Pastikan diagnosis sesuai kondisi terbaru</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Update tindakan medis jika diperlukan</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Periksa stok obat sebelum update resep</li>
                        <li><i class="fas fa-check text-success me-2"></i> Catatan tambahan bisa diupdate</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const prescriptionItems = document.getElementById('prescription-items');
    const addButton = document.getElementById('add-medicine');
    let itemCount = document.querySelectorAll('.prescription-item').length;

    // Tambah obat baru
    addButton.addEventListener('click', function() {
        itemCount++;
        const newItem = document.querySelector('.prescription-item').cloneNode(true);
        
        // Reset values
        const select = newItem.querySelector('.medicine-select');
        const input = newItem.querySelector('.quantity-input');
        const stokInfo = newItem.querySelector('.stok-info');
        const removeBtn = newItem.querySelector('.remove-item');
        
        select.value = '';
        input.value = '';
        stokInfo.textContent = '';
        removeBtn.style.display = 'block';
        
        prescriptionItems.appendChild(newItem);
        updateRemoveButtons();
        updateStokInfo(); // Update info stok untuk item baru
    });

    // Update stok info
    function updateStokInfo() {
        document.querySelectorAll('.medicine-select').forEach(select => {
            const selectedOption = select.options[select.selectedIndex];
            const stok = parseInt(selectedOption?.getAttribute('data-stok'));
            const stokInfo = select.closest('.prescription-item').querySelector('.stok-info');
            
            if (selectedOption && selectedOption.value && !isNaN(stok)) {
                stokInfo.textContent = `Stok tersedia: ${stok}`;
                stokInfo.className = 'form-text stok-info small ' + (stok > 0 ? 'text-success' : 'text-danger');
            } else {
                stokInfo.textContent = '';
            }
        });
    }

    // Update stok info saat obat dipilih
    prescriptionItems.addEventListener('change', function(e) {
        if (e.target.classList.contains('medicine-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const stok = parseInt(selectedOption?.getAttribute('data-stok'));
            const stokInfo = e.target.closest('.prescription-item').querySelector('.stok-info');
            
            if (selectedOption && selectedOption.value && !isNaN(stok)) {
                stokInfo.textContent = `Stok tersedia: ${stok}`;
                stokInfo.className = 'form-text stok-info small ' + (stok > 0 ? 'text-success' : 'text-danger');
                
                // Validasi quantity input
                const quantityInput = e.target.closest('.prescription-item').querySelector('.quantity-input');
                if (quantityInput.value) {
                    validateQuantity(quantityInput, stok);
                }
            } else {
                stokInfo.textContent = '';
            }
        }
    });

    // Validasi jumlah tidak melebihi stok
    prescriptionItems.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const prescriptionItem = e.target.closest('.prescription-item');
            const select = prescriptionItem.querySelector('.medicine-select');
            const selectedOption = select.options[select.selectedIndex];
            
            if (!selectedOption || !selectedOption.value) {
                e.target.setCustomValidity('Pilih obat terlebih dahulu');
                e.target.classList.add('is-invalid');
                return;
            }
            
            const stok = parseInt(selectedOption.getAttribute('data-stok'));
            validateQuantity(e.target, stok);
        }
    });

    function validateQuantity(input, stok) {
        const quantity = parseInt(input.value);
        
        if (isNaN(quantity) || quantity <= 0) {
            input.setCustomValidity('Jumlah harus berupa angka lebih dari 0');
            input.classList.add('is-invalid');
        } else if (quantity > stok) {
            input.setCustomValidity(`Jumlah melebihi stok tersedia (${stok})`);
            input.classList.add('is-invalid');
        } else {
            input.setCustomValidity('');
            input.classList.remove('is-invalid');
        }
    }

    // Hapus item obat
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-item');
        removeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                if (document.querySelectorAll('.prescription-item').length > 1) {
                    this.closest('.prescription-item').remove();
                    updateRemoveButtons();
                }
            });
        });
    }

    // Validasi form sebelum submit
    document.getElementById('medicalRecordForm').addEventListener('submit', function(e) {
        const medicineSelects = document.querySelectorAll('.medicine-select');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        let hasError = false;
        
        medicineSelects.forEach((select, index) => {
            const quantityInput = quantityInputs[index];
            const selectedOption = select.options[select.selectedIndex];
            
            if (!selectedOption || !selectedOption.value) {
                hasError = true;
                select.classList.add('is-invalid');
            }
            
            if (!quantityInput.value || parseInt(quantityInput.value) <= 0) {
                hasError = true;
                quantityInput.classList.add('is-invalid');
            }
        });
        
        if (hasError) {
            e.preventDefault();
            alert('Harap periksa kembali data resep obat. Pastikan semua obat terpilih dan jumlah valid.');
            return false;
        }
    });

    // Initial setup
    updateRemoveButtons();
    updateStokInfo(); // Initialize stok info untuk data existing
});
</script>
@endpush

@push('styles')
<style>
.prescription-item {
    border-left: 4px solid #0d6efd;
}

.medicine-select:valid, .quantity-input:valid {
    border-color: #198754;
}

.medicine-select:invalid, .quantity-input:invalid {
    border-color: #dc3545;
}

.card-header {
    border-bottom: none;
}

.form-label {
    font-weight: 600;
}
</style>
@endpush