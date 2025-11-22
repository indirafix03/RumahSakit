<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id',
        'dokter_id',
        'schedule_id',
        'tanggal_booking',
        'keluhan_singkat',
        'status',
        'alasan_reject',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
    ];


    // Relationships
    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function medicalRecord()
    {
        // Relasi dengan Rekam Medis (one-to-one)
        return $this->hasOne(MedicalRecord::class);
    }

    // Accessor untuk mendapatkan jam dari schedule
    public function getJamAttribute()
    {
        return $this->schedule ? $this->schedule->jam_mulai : null;
    }

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeForDokter(Builder $query, $dokterId): Builder
    {
        return $query->where('dokter_id', $dokterId);
    }

    public function poli()
    {
    return $this->belongsTo(Poli::class, 'poli_id');
    }
}