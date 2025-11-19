<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = ['nama_obat', 'deskripsi', 'tipe_obat', 'stok', 'gambar_obat'];

    // Medicine has many ResepObat
    public function resepObat()
    {
        return $this->hasMany(ResepObat::class);
    }

    // Scope untuk obat tersedia
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    // Scope untuk obat keras
    public function scopeKeras($query)
    {
        return $query->where('tipe_obat', 'keras');
    }

    // Scope untuk obat biasa
    public function scopeBiasa($query)
    {
        return $query->where('tipe_obat', 'biasa');
    }

    // Method untuk cek stok
    public function getStatusStokAttribute()
    {
        if ($this->stok > 10) {
            return 'Tersedia';
        } elseif ($this->stok > 0) {
            return 'Terbatas';
        } else {
            return 'Habis';
        }
    }
}