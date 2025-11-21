<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schedules = Schedule::where('dokter_id', $user->id)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        $days = [
            'Senin' => 'Senin',
            'Selasa' => 'Selasa', 
            'Rabu' => 'Rabu',
            'Kamis' => 'Kamis',
            'Jumat' => 'Jumat',
            'Sabtu' => 'Sabtu',
            'Minggu' => 'Minggu'
        ];

        return view('dokter.schedules.index', compact('schedules', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi' => 'required|in:30,45,60',
        ]);

        $user = Auth::user();

        // Hitung jam selesai
        $jam_mulai = $request->jam_mulai;
        $jam_selesai = date('H:i', strtotime($jam_mulai . ' +30 minutes'));

        // Cek jadwal bentrok
        $conflictingSchedule = Schedule::where('dokter_id', $user->id)
            ->where('hari', $request->hari)
            ->where(function($query) use ($jam_mulai, $jam_selesai) {
                $query->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
                      ->orWhereBetween(\DB::raw("ADDTIME(jam_mulai, SEC_TO_TIME(durasi * 60))"), [$jam_mulai, $jam_selesai]);
            })
            ->exists();

        if ($conflictingSchedule) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok dengan jadwal yang sudah ada.')
                ->withInput();
        }

        try {
            Schedule::create([
                'dokter_id' => $user->id,
                'hari' => $request->hari,
                'jam_mulai' => $jam_mulai,
                'durasi' => $request->durasi,
            ]);

            return redirect()->route('dokter.schedules.index')
                ->with('success', 'Jadwal berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi' => 'required|in:30,45,60',
        ]);

        $user = Auth::user();

        // Hitung jam selesai
        $jam_mulai = $request->jam_mulai;
        $jam_selesai = date('H:i', strtotime($jam_mulai . ' +30 minutes'));

        // Cek jadwal bentrok (kecuali jadwal yang sedang diedit)
        $conflictingSchedule = Schedule::where('dokter_id', $user->id)
            ->where('id', '!=', $schedule->id)
            ->where('hari', $request->hari)
            ->where(function($query) use ($jam_mulai, $jam_selesai) {
                $query->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
                      ->orWhereBetween(\DB::raw("ADDTIME(jam_mulai, SEC_TO_TIME(durasi * 60))"), [$jam_mulai, $jam_selesai]);
            })
            ->exists();

        if ($conflictingSchedule) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok dengan jadwal yang sudah ada.')
                ->withInput();
        }

        try {
            $schedule->update([
                'hari' => $request->hari,
                'jam_mulai' => $jam_mulai,
                'durasi' => $request->durasi,
            ]);

            return redirect()->route('dokter.schedules.index')
                ->with('success', 'Jadwal berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->dokter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $schedule->delete();
            return redirect()->route('dokter.schedules.index')
                ->with('success', 'Jadwal berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}