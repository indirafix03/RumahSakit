<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter dari request
        $search = $request->input('search');
        $status = $request->input('status');
        $type = $request->input('type');

        // Query dengan filter
        $medicines = Medicine::query()
            ->when($search, function($query) use ($search) {
                return $query->search($search);
            })
            ->when($status, function($query) use ($status) {
                return $query->byStatus($status);
            })
            ->when($type, function($query) use ($type) {
                return $query->byType($type);
            })
            ->latest()
            ->get();

        return view('admin.medicines.index', compact('medicines', 'search', 'status', 'type'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tipe_obat' => 'required|in:keras,biasa',
            'stok' => 'required|integer|min:0',
            'expired_date' => 'nullable|date|after:today', // TAMBAHKAN
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('gambar')->store('medicines', 'public');

        Medicine::create([
            'nama_obat' => $request->nama_obat,
            'deskripsi' => $request->deskripsi,
            'tipe_obat' => $request->tipe_obat,
            'stok' => $request->stok,
            'expired_date' => $request->expired_date, // TAMBAHKAN
            'gambar_obat' => $path,
        ]);

        return redirect()->route('admin.medicines.index')->with('success', 'Obat berhasil dibuat.');
    }

    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tipe_obat' => 'required|in:keras,biasa',
            'stok' => 'required|integer|min:0',
            'expired_date' => 'nullable|date', // TAMBAHKAN
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama_obat' => $request->nama_obat,
            'deskripsi' => $request->deskripsi,
            'tipe_obat' => $request->tipe_obat,
            'stok' => $request->stok,
            'expired_date' => $request->expired_date, // TAMBAHKAN
        ];

        if ($request->hasFile('gambar')) {
            // Hapus file lama jika ada dan benar-benar ada di disk
            if (!empty($medicine->gambar_obat) && Storage::disk('public')->exists($medicine->gambar_obat)) {
                Storage::disk('public')->delete($medicine->gambar_obat);
            }

            // Simpan file baru ke folder 'medicines' di disk 'public'
            $data['gambar_obat'] = $request->file('gambar')->store('medicines', 'public');
        }

        $medicine->update($data);

        return redirect()->route('admin.medicines.index')->with('success', 'Obat berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine)
    {
        if ($medicine->gambar_obat) {
            Storage::disk('public')->delete($medicine->gambar_obat);
        }
        $medicine->delete();
        return redirect()->route('admin.medicines.index')->with('success', 'Obat berhasil dihapus.');
    }
}