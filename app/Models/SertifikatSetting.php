<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikatSetting extends Model
{
    protected $fillable = [
        'background_path',
        'nama_top',
        'nama_left',
        'nama_font_size',
        'nomor_top',
        'nomor_left',
        'nomor_font_size',
    ];

    /**
     * Pengaturan sertifikat cuma ada 1 baris (id=1).
     * Kalau belum ada, otomatis dibuat dengan nilai default dari migration.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}