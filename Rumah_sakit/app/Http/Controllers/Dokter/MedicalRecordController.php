<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        
        // Ambil appointment yang approved untuk hari ini sesuai struktur tabel
        $todayAppointments = Appointment::with(['pasien', 'schedule'])
            ->where('dokter_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('tanggal_booking', today()) // menggunakan tanggal_booking
            ->whereDoesntHave('medicalRecord') // hanya appointment yang belum punya rekam medis
            ->get();

        $medicines = Medicine::where('stok', '>', 0)->get();

        return view('dokter.medical-records.create', compact('todayAppointments', 'medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string|max:1000',
            'tindakan_medis' => 'required|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
            'obat_id' => 'required|array|min:1',
            'obat_id.*' => 'exists:medicines,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'integer|min:1|max:100',
        ]);

        $user = Auth::user();
        $appointment = Appointment::with('schedule')->findOrFail($request->appointment_id);

        // Validasi appointment
        if ($appointment->dokter_id !== $user->id || $appointment->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Appointment tidak valid atau belum disetujui.')
                ->withInput();
        }

        try {
            \DB::beginTransaction();

            // Buat rekam medis
            $medicalRecord = MedicalRecord::create([
                'pasien_id' => $appointment->pasien_id,
                'dokter_id' => $user->id,
                'appointment_id' => $appointment->id,
                'diagnosis' => $request->diagnosis,
                'tindakan_medis' => $request->tindakan_medis,
                'catatan' => $request->catatan,
            ]);

            // Buat resep
            foreach ($request->obat_id as $index => $obatId) {
                $medicine = Medicine::findOrFail($obatId);
                $jumlah = $request->jumlah[$index];

                // Cek stok
                if ($medicine->stok < $jumlah) {
                    throw new \Exception("Stok {$medicine->nama_obat} tidak mencukupi. Stok tersedia: {$medicine->stok}");
                }

                // Kurangi stok
                $medicine->decrement('stok', $jumlah);

                // Buat item resep
                $medicalRecord->prescriptionItems()->create([
                    'medicine_id' => $obatId,
                    'jumlah' => $jumlah,
                ]);
            }

            // Update status appointment menjadi selesai
            $appointment->update(['status' => 'selesai']);

            \DB::commit();

            return redirect()->route('dokter.medical-records.index')
                ->with('success', 'Rekam medis berhasil dibuat.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $medicalRecord->load(['pasien', 'appointment', 'prescriptionItems.medicine']);
        return view('dokter.medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $medicines = Medicine::where('stok', '>', 0)->get();
        $medicalRecord->load('prescriptionItems.medicine');

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
            // Kembalikan stok obat
            foreach ($medicalRecord->prescriptionItems as $item) {
                $medicine = Medicine::find($item->medicine_id);
                if ($medicine) {
                    $medicine->increment('stok', $item->jumlah);
                }
            }

            // Kembalikan status appointment
            $appointment = $medicalRecord->appointment;
            if ($appointment) {
                $appointment->update(['status' => 'approved']);
            }

            $medicalRecord->delete();

            return redirect()->route('dokter.medical-records.index')
                ->with('success', 'Rekam medis berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}