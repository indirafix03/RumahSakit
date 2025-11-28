<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $medicalRecords = MedicalRecord::with(['appointment', 'dokter', 'pasien'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.medical-records.index', compact('medicalRecords'));
    }

    public function create($appointment_id)
    {
        $appointment = Appointment::with(['pasien', 'dokter'])->findOrFail($appointment_id);
        $medicines = Medicine::all();
        
        return view('admin.medical-records.create', compact('appointment', 'medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string|max:500',
            'tindakan_medis' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:1000',
            'medicines' => 'nullable|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            \DB::beginTransaction();

            // Create medical record
            $medicalRecord = MedicalRecord::create([
                'appointment_id' => $request->appointment_id,
                'dokter_id' => $request->dokter_id,
                'pasien_id' => $request->pasien_id,
                'diagnosis' => $request->diagnosis,
                'tindakan_medis' => $request->tindakan_medis,
                'catatan' => $request->catatan,
            ]);

            // Create prescriptions if any
            if ($request->has('medicines')) {
                foreach ($request->medicines as $medicine) {
                    $medicalRecord->prescriptions()->create([
                        'medicine_id' => $medicine['medicine_id'],
                        'quantity' => $medicine['quantity'],
                        'status' => 'pending',
                    ]);
                }
            }

            // Update appointment status to completed
            $appointment = Appointment::find($request->appointment_id);
            $appointment->update(['status' => 'selesai']);

            \DB::commit();

            return redirect()->route('admin.appointments.show', $request->appointment_id)
                ->with('success', 'Rekam medis berhasil dibuat.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['appointment', 'dokter', 'pasien', 'prescriptions.medicine']);
        return view('admin.medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $medicines = Medicine::all();
        $medicalRecord->load(['prescriptions.medicine']);
        return view('admin.medical-records.edit', compact('medicalRecord', 'medicines'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'diagnosis' => 'required|string|max:500',
            'tindakan_medis' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $medicalRecord->update($request->only(['diagnosis', 'tindakan_medis', 'catatan']));

        return redirect()->route('admin.medical-records.show', $medicalRecord)
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        return redirect()->route('admin.medical-records.index')
            ->with('success', 'Rekam medis berhasil dihapus.');
    }
}