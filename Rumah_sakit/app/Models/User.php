<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'poli_id', 'no_telepon', 
        'alamat', 'spesialisasi', 'bio'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELATIONSHIPS ==========

    // User belongs to Poli (hanya untuk dokter)
    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    // User (dokter) has many Schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'dokter_id');
    }

    // User (dokter) has many Appointments
    public function appointmentsAsDokter()
    {
        return $this->hasMany(Appointment::class, 'dokter_id');
    }

    // User (pasien) has many Appointments
    public function appointmentsAsPasien()
    {
        return $this->hasMany(Appointment::class, 'pasien_id');
    }

    // User (dokter) has many Feedbacks
    public function feedbacksAsDokter()
    {
        return $this->hasMany(Feedback::class, 'dokter_id');
    }

    // User (pasien) has many Feedbacks
    public function feedbacksAsPasien()
    {
        return $this->hasMany(Feedback::class, 'pasien_id');
    }

    // User (dokter) has many MedicalRecords through appointments
    public function medicalRecords()
    {
        return $this->hasManyThrough(MedicalRecord::class, Appointment::class, 'dokter_id');
    }

    // ========== SCOPES ==========
    
    public function scopeDokter($query)
    {
        return $query->where('role', 'dokter');
    }

    public function scopePasien($query)
    {
        return $query->where('role', 'pasien');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    // ========== METHODS ==========
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isPasien()
    {
        return $this->role === 'pasien';
    }
}