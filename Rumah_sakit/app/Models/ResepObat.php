<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepObat extends Model
{
    use HasFactory;

    protected $table = 'resep_obat';
    
    protected $fillable = ['medical_record_id', 'medicine_id', 'jumlah', 'aturan_pakai'];

    // ResepObat belongs to MedicalRecord
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    // ResepObat belongs to Medicine
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Method untuk mengurangi stok obat
    public function reduceStock()
    {
        $medicine = $this->medicine;
        if ($medicine->stok >= $this->jumlah) {
            $medicine->decrement('stok', $this->jumlah);
            return true;
        }
        return false;
    }
}