<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::latest()->get()->map(function($poli) {
            // default null
            $poli->image_url = null;
            $poli->has_image = false;

            if (!empty($poli->ikon) && Storage::disk('public')->exists($poli->ikon)) {
                $poli->image_url = Storage::url($poli->ikon); 
                $poli->has_image = true;
            }

            return $poli;
        });

        return view('admin.polis.index', compact('polis'));
    }

    public function create()
    {
        return view('admin.polis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:255|unique:polis',
            'deskripsi' => 'required|string',
            'ikon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('ikon')->store('polis', 'public');

        Poli::create([
            'nama_poli' => $request->nama_poli,
            'deskripsi' => $request->deskripsi,
            'ikon' => $path,
        ]);

        return redirect()->route('admin.polis.index')->with('success', 'Poli berhasil dibuat.');
    }

    public function edit(Poli $poli)
    {
        return view('admin.polis.edit', compact('poli'));
    }

    public function update(Request $request, Poli $poli)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:255|unique:polis,nama_poli,' . $poli->id,
            'deskripsi' => 'required|string',
            'ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama_poli' => $request->nama_poli,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('ikon')) {
            if ($poli->ikon && Storage::disk('public')->exists($poli->ikon)) {
                Storage::disk('public')->delete($poli->ikon);
            }
            $data['ikon'] = $request->file('ikon')->store('polis', 'public');
        }
        $poli->update($data);
        return redirect()->route('admin.polis.index')->with('success', 'Poli berhasil diperbarui.');
    }

    public function destroy(Poli $poli)
    {
        if ($poli->ikon && Storage::disk('public')->exists($poli->ikon)) {
            Storage::disk('public')->delete($poli->ikon);
        }
        $poli->delete();
        return redirect()->route('admin.polis.index')->with('success', 'Poli berhasil dihapus.');
    }
}