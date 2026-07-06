<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'keterangan',
        'bukti_dokumen',
        'latitude',       
        'longitude',      
        'jarak_meter',    
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'latitude'    => 'float',   // ← tambah
            'longitude'   => 'float',   // ← tambah
            'jarak_meter' => 'float',   // ← tambah
        ];
    }

    // ─── Relasi ───────────────────────────────────────────────
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    // ─── Scopes ───────────────────────────────────────────────
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    public function scopeByBulan($query, int $bulan, int $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)
                     ->whereYear('tanggal', $tahun);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // ─── Helper Methods ───────────────────────────────────────
    public function sudahMasuk(): bool
    {
        return !is_null($this->jam_masuk);
    }

    public function sudahPulang(): bool
    {
        return !is_null($this->jam_pulang);
    }

    public function getBadgeStatusAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'bg-green-100 text-green-800',
            'sakit' => 'bg-yellow-100 text-yellow-800',
            'izin'  => 'bg-blue-100 text-blue-800',
            'alpa'  => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'hadir' => '✅ Hadir',
            'sakit' => '🤒 Sakit',
            'izin'  => '📋 Izin',
            'alpa'  => '❌ Alpa',
            default => '-',
        };
    }
}