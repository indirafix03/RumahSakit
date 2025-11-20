@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Buat Rekam Medis Baru</h1>
        <a href="{{ route('dokter.medical-records.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Rekam Medis
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Form Rekam Medis</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dokter.medical-records.store') }}" method="POST" id="medicalRecordForm">
                        @csrf
                        
                        <!-- Pilih Janji Temu -->
                        <div class="mb-4">
                            <label for="appointment_id" class="form-label fw-bold">Pilih Janji Temu <span class="text-danger">*</span></label>
                            <select class="form-select @error('appointment_id') is-invalid @enderror" 
                                    id="appointment_id" name="appointment_id" required>
                                <option value="">-- Pilih Janji Temu --</option>
                                @foreach($todayAppointments as $appointment)
                                    <option value="{{ $appointment->id }}" 
                                        {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                        {{ $appointment->pasien->name }} - 
                                        {{ $appointment->tanggal->format('d/m/Y') }} {{ $appointment->jam }}
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($todayAppointments->isEmpty())
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Tidak ada janji temu yang disetujui untuk hari ini.
                                </div>
                            @else
                                <div class="form-text">Pilih pasien yang akan diperiksa</div>
                            @endif
                        </div>

                        <!-- Diagnosis -->
                        <div class="mb-4">
                            <label for="diagnosis" class="form-label fw-bold">Diagnosis <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('diagnosis') is-invalid @enderror" 
                                      id="diagnosis" name="diagnosis" rows="4" 
                                      placeholder="Masukkan diagnosis utama pasien..." required>{{ old('diagnosis') }}</textarea>
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
                                      placeholder="Jelaskan tindakan medis yang dilakukan..." required>{{ old('tindakan_medis') }}</textarea>
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
                                      placeholder="Catatan tambahan (opsional)...">{{ old('catatan') }}</textarea>
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
                                <!-- Item Obat Pertama -->
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
                                    {{ $todayAppointments->isEmpty() || $medicines->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-save me-1"></i> Simpan Rekam Medis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Info Pasien -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-user-injured me-2"></i>Informasi Pasien</h6>
                </div>
                <div class="card-body">
                    <div id="patient-info" class="text-center text-muted">
                        <i class="fas fa-user fa-3x mb-3"></i>
                        <p>Pilih janji temu untuk melihat informasi pasien</p>
                    </div>
                </div>
            </div>

            <!-- Panduan -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Panduan Pengisian</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Diagnosis harus jelas dan spesifik</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Tindakan medis diisi sesuai pemeriksaan</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Resep obat wajib diisi minimal 1 obat</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Periksa stok obat sebelum memberi resep</li>
                        <li><i class="fas fa-check text-success me-2"></i> Status janji temu akan otomatis berubah menjadi "Selesai"</li>
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
    const appointmentSelect = document.getElementById('appointment_id');
    const patientInfo = document.getElementById('patient-info');
    let itemCount = 1;

    // Data janji temu untuk info pasien - SESUAIKAN DENGAN STRUKTUR TABEL
    const appointmentsData = {
        @foreach($todayAppointments as $appointment)
        "{{ $appointment->id }}": {
            patientName: "{{ $appointment->pasien->name }}",
            patientEmail: "{{ $appointment->pasien->email }}",
            appointmentDate: "{{ $appointment->tanggal_booking->format('d/m/Y') }}", // menggunakan tanggal_booking
            scheduleTime: "{{ $appointment->schedule ? $appointment->schedule->jam_mulai : 'N/A' }}", // dari schedule
            keluhan: "{{ $appointment->keluhan_singkat }}" // menggunakan keluhan_singkat
        },
        @endforeach
    };

    // Update info pasien saat janji temu dipilih
    appointmentSelect.addEventListener('change', function() {
        const appointmentId = this.value;
        if (appointmentId && appointmentsData[appointmentId]) {
            const data = appointmentsData[appointmentId];
            patientInfo.innerHTML = `
                <div class="text-start">
                    <h6 class="fw-bold">${data.patientName}</h6>
                    <p class="mb-1"><small>Email: ${data.patientEmail}</small></p>
                    <p class="mb-1"><small>Tanggal: ${data.appointmentDate}</small></p>
                    <p class="mb-1"><small>Jam: ${data.scheduleTime}</small></p>
                    <p class="mb-0"><small>Keluhan: ${data.keluhan}</small></p>
                </div>
            `;
        } else {
            patientInfo.innerHTML = `
                <i class="fas fa-user fa-3x mb-3"></i>
                <p>Pilih janji temu untuk melihat informasi pasien</p>
            `;
        }
    });

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
    });

    // Update stok info saat obat dipilih
    prescriptionItems.addEventListener('change', function(e) {
        if (e.target.classList.contains('medicine-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const stok = selectedOption.getAttribute('data-stok');
            const stokInfo = e.target.closest('.prescription-item').querySelector('.stok-info');
            
            if (stok) {
                stokInfo.textContent = `Stok tersedia: ${stok}`;
                stokInfo.className = 'form-text stok-info small ' + (stok > 0 ? 'text-success' : 'text-danger');
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
            const stok = select.options[select.selectedIndex]?.getAttribute('data-stok');
            const quantity = e.target.value;
            
            if (stok && quantity > stok) {
                e.target.setCustomValidity(`Jumlah melebihi stok tersedia (${stok})`);
                e.target.classList.add('is-invalid');
            } else {
                e.target.setCustomValidity('');
                e.target.classList.remove('is-invalid');
            }
        }
    });

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
        let hasMedicine = false;
        
        medicineSelects.forEach(select => {
            if (select.value) {
                hasMedicine = true;
            }
        });
        
        if (!hasMedicine) {
            e.preventDefault();
            alert('Harap pilih minimal 1 obat untuk resep');
            return false;
        }
    });

    // Initial setup
    updateRemoveButtons();
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