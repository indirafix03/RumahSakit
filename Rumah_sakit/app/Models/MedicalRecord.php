<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_id', 'diagnosis', 'tindakan_medis', 'catatan'];

    // MedicalRecord belongs to Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // MedicalRecord has many ResepObat
    public function resepObat()
    {
        return $this->hasMany(ResepObat::class);
    }

    // Accessor untuk mendapatkan data pasien melalui appointment
    public function getPatientAttribute()
    {
        return $this->appointment->patient;
    }

    // Accessor untuk mendapatkan data dokter melalui appointment
    public function getDoctorAttribute()
    {
        return $this->appointment->doctor;
    }
}