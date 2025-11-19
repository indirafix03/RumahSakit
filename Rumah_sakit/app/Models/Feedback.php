<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = ['pasien_id', 'dokter_id', 'appointment_id', 'rating', 'ulasan'];

    // Feedback belongs to Patient (User)
    public function patient()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    // Feedback belongs to Doctor (User)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    // Feedback belongs to Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Scope untuk rating tertinggi
    public function scopeRatingTinggi($query)
    {
        return $query->where('rating', '>=', 4);
    }

    // Accessor untuk bintang rating
    public function getBintangAttribute()
    {
        return str_repeat('⭐', $this->rating);
    }
}