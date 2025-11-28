<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks'; 

    protected $fillable = [
        'pasien_id',
        'dokter_id', 
        'appointment_id',
        'rating',
        'ulasan'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
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