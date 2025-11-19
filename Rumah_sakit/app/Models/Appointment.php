<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id', 'dokter_id', 'schedule_id', 'tanggal_booking', 
        'keluhan_singkat', 'status', 'alasan_reject'
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
    ];

    // Appointment belongs to Patient (User)
    public function patient()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    // Appointment belongs to Doctor (User)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    // Appointment belongs to Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Appointment has one MedicalRecord
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    // Appointment has one Feedback
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    // Scopes untuk status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // Scope untuk janji hari ini
    public function scopeHariIni($query)
    {
        return $query->where('tanggal_booking', today());
    }

    // Method untuk approve appointment
    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    // Method untuk reject appointment
    public function reject($alasan = null)
    {
        $this->update([
            'status' => 'rejected',
            'alasan_reject' => $alasan
        ]);
    }

    // Method untuk complete appointment
    public function complete()
    {
        $this->update(['status' => 'selesai']);
    }
}