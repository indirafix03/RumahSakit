<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['dokter_id', 'hari', 'jam_mulai', 'durasi'];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
    ];

    // Schedule belongs to Doctor (User)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    // Schedule has many Appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Helper method untuk mendapatkan jam selesai
    public function getJamSelesaiAttribute()
    {
        return \Carbon\Carbon::parse($this->jam_mulai)->addMinutes($this->durasi)->format('H:i');
    }

    // Scope untuk jadwal hari ini
    public function scopeHariIni($query)
    {
        $hariIndo = now()->translatedFormat('l');
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        return $query->where('hari', $hariMap[$hariIndo]);
    }
}