@if($pendingAppointments->isEmpty())
    <div class="card mt-3">
        <div class="card-body text-center py-4">
            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
            <p class="text-muted">Tidak ada janji temu pending.</p>
        </div>
    </div>
@else
    <div class="row mt-3">
        @foreach($pendingAppointments as $appointment)
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $appointment->pasien->name }}</h5>
                    <p class="card-text">
                        <strong>Tanggal:</strong> {{ $appointment->tanggal->format('d/m/Y') }}<br>
                        <strong>Jam:</strong> {{ $appointment->jam }}<br>
                        <strong>Poli:</strong> {{ $appointment->poli->nama_poli }}<br>
                        <strong>Keluhan:</strong> {{ $appointment->keluhan }}
                    </p>
                    <div class="d-flex gap-2">
                        <form action="{{ route('dokter.appointments.updateStatus', $appointment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success btn-sm" 
                                    onclick="return confirm('Setujui janji temu ini?')">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#rejectModal"
                                data-appointment-id="{{ $appointment->id }}">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                        <a href="{{ route('dokter.appointments.show', $appointment) }}" 
                           class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Janji Temu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rejected">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" 
                                  rows="3" required placeholder="Berikan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Janji Temu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rejectModal = document.getElementById('rejectModal');
    rejectModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const appointmentId = button.getAttribute('data-appointment-id');
        const form = document.getElementById('rejectForm');
        form.action = `/dokter/appointments/${appointmentId}/status`;
    });
});
</script>