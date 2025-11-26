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

    public function dokter()
    {
        $dokters = User::where('role', 'dokter')
            ->with('poli')
            ->withCount(['schedules' => function($query) {
                $query->where('hari', now()->dayOfWeek);
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