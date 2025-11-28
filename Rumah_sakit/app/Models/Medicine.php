<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_obat', 
        'deskripsi', 
        'tipe_obat', 
        'stok', 
        'expired_date',
        'gambar_obat'
    ];

    protected $casts = [
        'expired_date' => 'date',
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_obat', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
    }

    // Scope untuk filter status
    public function scopeByStatus($query, $status)
    {
        $now = now()->format('Y-m-d');
        $thirtyDaysLater = now()->addDays(30)->format('Y-m-d');
        
        return match($status) {
            'tersedia' => $query->where('stok', '>', 0)
                               ->where(function($q) use ($now) {
                                   $q->whereNull('expired_date')
                                     ->orWhere('expired_date', '>', $now);
                               }),
            'habis' => $query->where('stok', '<=', 0),
            'kadaluarsa' => $query->where('expired_date', '<=', $now),
            'hampir_kadaluarsa' => $query->where('expired_date', '>', $now)
                                        ->where('expired_date', '<=', $thirtyDaysLater),
            default => $query
        };
    }

    public function scopeByType($query, $type)
    {
        if ($type) {
            return $query->where('tipe_obat', $type);
        }
        return $query;
    }

    // PERBAIKAN: Method untuk status dengan perhitungan hari yang BENAR
    public function getStatusInfo()
    {
        $now = now();
        
        // Jika tidak ada expired_date, hanya cek stok
        if (!$this->expired_date) {
            return $this->getStockStatus();
        }
        
        // Parse expired_date ke Carbon
        $expiredDate = \Carbon\Carbon::parse($this->expired_date);
        
        // PERBAIKAN: Hitung hari dengan urutan yang BENAR
        $daysUntilExpired = $now->diffInDays($expiredDate, false); // false = include sign
        
        // 1. Cek jika sudah kadaluarsa (hari negatif)
        if ($daysUntilExpired < 0) {
            return [
                'status' => 'kadaluarsa',
                'color' => 'danger',
                'label' => 'Kadaluarsa',
                'reason' => 'Tanggal kadaluarsa sudah lewat ' . abs($daysUntilExpired) . ' hari yang lalu',
                'days_until_expired' => $daysUntilExpired
            ];
        }
        
        // 2. Cek jika hampir kadaluarsa (dalam 30 hari)
        if ($daysUntilExpired <= 30) {
            return [
                'status' => 'hampir_kadaluarsa',
                'color' => 'warning',
                'label' => 'Hampir Kadaluarsa',
                'reason' => 'Akan kadaluarsa dalam ' . $daysUntilExpired . ' hari',
                'days_until_expired' => $daysUntilExpired
            ];
        }
        
        // 3. Jika tidak kadaluarsa dan tidak hampir kadaluarsa, cek stok normal
        $stockStatus = $this->getStockStatus();
        $stockStatus['days_until_expired'] = $daysUntilExpired;
        $stockStatus['reason'] .= ' (Kadaluarsa dalam ' . $daysUntilExpired . ' hari)';
        
        return $stockStatus;
    }
    
    // Method untuk status berdasarkan stok saja
    private function getStockStatus()
    {
        if ($this->stok > 10) {
            return [
                'status' => 'tersedia',
                'color' => 'success',
                'label' => 'Tersedia',
                'reason' => 'Stok mencukupi'
            ];
        } elseif ($this->stok > 0) {
            return [
                'status' => 'terbatas',
                'color' => 'warning',
                'label' => 'Stok Terbatas',
                'reason' => 'Stok hampir habis'
            ];
        } else {
            return [
                'status' => 'habis',
                'color' => 'secondary',
                'label' => 'Stok Habis',
                'reason' => 'Tidak ada stok'
            ];
        }
    }
    
    // PERBAIKAN: Method helper untuk formatted date dengan perhitungan BENAR
    public function getFormattedExpiredDate()
    {
        if (!$this->expired_date) {
            return '-';
        }
        
        $expiredDate = \Carbon\Carbon::parse($this->expired_date);
        $statusInfo = $this->getStatusInfo();
        
        $formatted = $expiredDate->format('d/m/Y');
        $daysUntilExpired = $statusInfo['days_until_expired'];
        
        if ($statusInfo['status'] === 'hampir_kadaluarsa') {
            return $formatted . ' <i class="fas fa-exclamation-triangle text-warning" title="Akan kadaluarsa dalam ' . $daysUntilExpired . ' hari"></i>';
        }
        
        if ($statusInfo['status'] === 'kadaluarsa') {
            $daysAgo = abs($daysUntilExpired);
            return '<span class="text-danger">' . $formatted . '</span> <i class="fas fa-skull-crossbones text-danger" title="Sudah kadaluarsa sejak ' . $daysAgo . ' hari yang lalu"></i>';
        }
        
        return $formatted;
    }
    
    // Method untuk debugging
    public function getDebugInfo()
    {
        $expiredDate = $this->expired_date ? \Carbon\Carbon::parse($this->expired_date) : null;
        $daysUntilExpired = $expiredDate ? now()->diffInDays($expiredDate, false) : null;
        
        return [
            'nama_obat' => $this->nama_obat,
            'expired_date' => $this->expired_date,
            'expired_date_carbon' => $expiredDate ? $expiredDate->format('Y-m-d') : null,
            'stok' => $this->stok,
            'days_until_expired' => $daysUntilExpired, // PERBAIKAN: dengan urutan yang benar
            'days_until_expired_calc' => $expiredDate ? 
                'now()->diffInDays(expired_date) = ' . now()->diffInDays($expiredDate, false) : null,
            'status_info' => $this->getStatusInfo(),
            'now' => now()->format('Y-m-d H:i:s'),
            '30_days_later' => now()->addDays(30)->format('Y-m-d')
        ];
    }
}