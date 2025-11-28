<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'medicine_id',
        'quantity',
        'status',
        'instructions' // TAMBAHKAN INI
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Scopes
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeTaken($query)
    {
        return $query->where('status', 'taken');
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->whereHas('medicalRecord', function($q) use ($patientId) {
            $q->where('pasien_id', $patientId);
        });
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning',
            'ready' => 'bg-success', 
            'taken' => 'bg-info'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Dalam Proses',
            'ready' => 'Siap Diambil',
            'taken' => 'Sudah Diambil'
        ];

        return $texts[$this->status] ?? $this->status;
    }

    // Methods
    public function markAsTaken()
    {
        $this->update(['status' => 'taken']);
        return $this;
    }

    public function isReady()
    {
        return $this->status === 'ready';
    }

    public function canBeTaken()
    {
        return $this->status === 'ready';
    }
}