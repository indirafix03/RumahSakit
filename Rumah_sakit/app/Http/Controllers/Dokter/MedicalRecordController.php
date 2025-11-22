<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\Prescription; // TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // TAMBAHKAN INI

class MedicalRecordController extends Controller
{
      public function index()
    {
        $user = Auth::user();
        
        $medicalRecords = MedicalRecord::with(['pasien', 'appointment'])
            ->where('dokter_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dokter.medical-records.index', compact('medicalRecords'));
    }

    public function create()
    {
        $dokter = auth()->user();
        
        // Gunakan Carbon dengan timezone Jakarta
        $today = now()->timezone('Asia/Jakarta')->format('Y-m-d');
        
        \Log::info('Medical Record Create - Debug Info', [
            'dokter_id' => $dokter->id,
            'dokter_name' => $dokter->name,
            'today_date' => $today,
            'now_time' => now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'server_time' => now()->format('Y-m-d H:i:s')
        ]);

        // Get today's approved appointments for this doctor
        $todayAppointments = Appointment::with(['pasien', 'schedule'])
            ->where('dokter_id', $dokter->id)
            ->where('status', 'approved')
            ->whereDate('tanggal_booking', $today)
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'pasien' => [
                        'name' => $appointment->pasien->name,
                        'email' => $appointment->pasien->email,
                    ],
                    'tanggal_booking' => $appointment->tanggal_booking,
                    'schedule' => $appointment->schedule,
                    'keluhan_singkat' => $appointment->keluhan_singkat,
                    'display_text' => $appointment->pasien->name . ' - ' . 
                                    $appointment->tanggal_booking->format('d/m/Y') . ' ' . 
                                    ($appointment->schedule ? $appointment->schedule->jam_mulai : '') .
                                    ' (' . $appointment->keluhan_singkat . ')'
                ];
            });

        \Log::info('Appointments found', [
            'count' => $todayAppointments->count(),
            'appointments' => $todayAppointments->pluck('display_text')
        ]);

        $medicines = Medicine::where('stok', '>', 0)->get();

        return view('dokter.medical-records.create', compact('todayAppointments', 'medicines'));
    }

    public function store(Request $request)
{
    $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'diagnosis' => 'required|string|max:1000',
        'tindakan_medis' => 'required|string|max:1000',
        'catatan' => 'nullable|string|max:500',
        'obat_id' => 'required|array|min:1',
        'obat_id.*' => 'exists:medicines,id',
        'jumlah' => 'required|array|min:1',
        'jumlah.*' => 'integer|min:1|max:100'
    ]);

    try {
        DB::beginTransaction();

        // Dapatkan appointment dengan relasi
        $appointment = Appointment::with(['dokter', 'pasien'])->findOrFail($request->appointment_id);

        // Create medical record - TAMBAHKAN dokter_id dan pasien_id
        $medicalRecord = MedicalRecord::create([
            'appointment_id' => $request->appointment_id,
            'dokter_id' => $appointment->dokter_id, // TAMBAH INI
            'pasien_id' => $appointment->pasien_id, // TAMBAH INI
            'diagnosis' => $request->diagnosis,
            'tindakan_medis' => $request->tindakan_medis,
            'catatan' => $request->catatan,
        ]);

        // Create prescriptions
        foreach ($request->obat_id as $index => $obatId) {
            $quantity = $request->jumlah[$index];
            $medicine = Medicine::find($obatId);
            
            // Validasi stok sebelum membuat resep
            if (!$medicine) {
                throw new \Exception("Obat tidak ditemukan");
            }
            
            if ($medicine->stok < $quantity) {
                throw new \Exception("Stok obat {$medicine->nama_obat} tidak mencukupi. Stok tersedia: {$medicine->stok}");
            }

            Prescription::create([
                'medical_record_id' => $medicalRecord->id,
                'medicine_id' => $obatId,
                'quantity' => $quantity,
                'status' => 'pending'
            ]);

            // Update medicine stock
            $medicine->decrement('stok', $quantity);
        }

        // Update appointment status to completed
        $appointment->update(['status' => 'selesai']);

        DB::commit();

        return redirect()->route('dokter.medical-records.index')
            ->with('success', 'Rekam medis berhasil dibuat.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error creating medical record: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
}

    public function show(MedicalRecord $medicalRecord)
{
    // Authorization - pastikan dokter hanya bisa melihat rekam medis mereka sendiri
    if ($medicalRecord->dokter_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $medicalRecord->load(['pasien', 'appointment', 'prescriptions.medicine']);
    return view('dokter.medical-records.show', compact('medicalRecord'));
}

    public function edit(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $medicines = Medicine::where('stok', '>', 0)->get();
        $medicalRecord->load('prescriptions.medicine');

        return view('dokter.medical-records.edit', compact('medicalRecord', 'medicines'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'diagnosis' => 'required|string|max:1000',
            'tindakan_medis' => 'required|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
        ]);

        try {
            $medicalRecord->update([
                'diagnosis' => $request->diagnosis,
                'tindakan_medis' => $request->tindakan_medis,
                'catatan' => $request->catatan,
            ]);

            return redirect()->route('dokter.medical-records.index')
                ->with('success', 'Rekam medis berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            // Kembalikan stok obat
            foreach ($medicalRecord->prescriptions as $prescription) {
                $medicine = Medicine::find($prescription->medicine_id);
                if ($medicine) {
                    $medicine->increment('stok', $prescription->quantity);
                }
            }

            // Kembalikan status appointment
            $appointment = $medicalRecord->appointment;
            if ($appointment) {
                $appointment->update(['status' => 'approved']);
            }

            // Hapus prescriptions terlebih dahulu
            $medicalRecord->prescriptions()->delete();
            
            // Hapus medical record
            $medicalRecord->delete();

            DB::commit();

            return redirect()->route('dokter.medical-records.index')
                ->with('success', 'Rekam medis berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}