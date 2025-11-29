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

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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

    // Tambahkan method ini di model User jika belum ada

/**
 * Get formatted phone number
 */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->no_telepon) {
            return null;
        }
        
        // Format phone number for display
        $phone = $this->no_telepon;
        if (str_starts_with($phone, '+62')) {
            $phone = '0' . substr($phone, 3);
        }
        
        return $phone;
    }

    /**
     * Get short bio (truncated)
     */
    public function getShortBioAttribute()
    {
        if (!$this->bio) {
            return null;
        }
        
        return \Illuminate\Support\Str::limit($this->bio, 120);
    }

    /**
     * Check if user has complete profile
     */
    public function getHasCompleteProfileAttribute()
    {
        $requiredFields = ['name', 'email'];
        
        if ($this->isDokter()) {
            $requiredFields[] = 'spesialisasi';
        }
        
        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }
        
        return true;
    }
}