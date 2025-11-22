<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    // TAMBAHKAN dokter_id dan pasien_id ke fillable
    protected $fillable = [
        'appointment_id', 
        'dokter_id', 
        'pasien_id', 
        'diagnosis', 
        'tindakan_medis', 
        'catatan'
    ];

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
        return $this->appointment->pasien; // SESUAIKAN: gunakan 'pasien' bukan 'patient'
    }

    // Accessor untuk mendapatkan data dokter melalui appointment
    public function getDoctorAttribute()
    {
        return $this->appointment->dokter; // SESUAIKAN: gunakan 'dokter' bukan 'doctor'
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }
}