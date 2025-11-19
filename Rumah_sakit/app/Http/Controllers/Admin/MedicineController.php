<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::latest()->get();
        return view('admin.medicines.index', compact('medicines'));
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
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('gambar')->store('medicines', 'public');

        Medicine::create([
            'nama_obat' => $request->nama_obat,
            'deskripsi' => $request->deskripsi,
            'tipe_obat' => $request->tipe_obat,
            'stok' => $request->stok,
            'gambar' => $path,
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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama_obat' => $request->nama_obat,
            'deskripsi' => $request->deskripsi,
            'tipe_obat' => $request->tipe_obat,
            'stok' => $request->stok,
        ];

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($medicine->gambar) {
                Storage::disk('public')->delete($medicine->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('medicines', 'public');
        }

        $medicine->update($data);

        return redirect()->route('admin.medicines.index')->with('success', 'Obat berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine)
    {
        if ($medicine->gambar) {
            Storage::disk('public')->delete($medicine->gambar);
        }
        $medicine->delete();
        return redirect()->route('admin.medicines.index')->with('success', 'Obat berhasil dihapus.');
    }
}