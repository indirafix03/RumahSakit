<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function poli()
    {
        $polis = Poli::withCount(['doctors' => function($query) {
            $query->where('role', 'dokter');
        }])->get();

        return view('guest.poli', compact('polis'));
    }

    public function dokter(Request $request)
    {
        $query = User::where('role', 'dokter')
            ->with('poli');

        // Filter berdasarkan poli jika parameter ada
        if ($request->has('poli') && $request->poli) {
            $query->where('poli_id', $request->poli);
        }

        // Count total jadwal per dokter (bukan hanya hari ini)
        $dokters = $query->withCount(['schedules' => function($q) {
            // Count jadwal untuk setiap hari, tidak di-filter
        }])
        ->get();

        return view('guest.dokter', compact('dokters'));
    }

    public function dokterDetail($id)
    {
        $dokter = User::where('role', 'dokter')
            ->with(['poli', 'schedules'])
            ->findOrFail($id);

        return view('guest.dokter-detail', compact('dokter'));
    }
}