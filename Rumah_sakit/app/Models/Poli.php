<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Schedule;

class Poli extends Model
{
    use HasFactory;

    protected $fillable = ['nama_poli', 'deskripsi', 'ikon'];

    // Poli has many Doctors (users dengan role dokter)
    public function doctors()
    {   
        return $this->hasMany(User::class, 'poli_id')->where('role', 'dokter');
    }

    // Poli has many Appointments through doctors
    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, User::class, 'poli_id', 'dokter_id');
    }

    // Poli has many Schedules through doctors
    public function schedules()
    {
        return $this->hasManyThrough(Schedule::class, User::class, 'poli_id', 'dokter_id');
    }

    
}