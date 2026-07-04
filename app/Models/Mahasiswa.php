<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_pemohon',
        'jenis_instansi',
        'peran_kelompok',
        'nim',
        'jurusan',
        'prodi',
        'fakultas',
        'universitas',
        'nomor_hp',
        'jenis_kelamin',
        'kelas',
        'kompetensi_keahlian',
        'tanggal_mulai',
        'tanggal_selesai',
        'kode_kelompok',
        'status_magang',
        'status_pendaftaran',
        'surat_pengantar_path',
        'proposal_path',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Semua anggota lain (termasuk ketua) dalam kelompok yang sama.
     * Dipakai untuk ambil daftar 1 kelompok dari kode_kelompok yang sama.
     */
    public function temanSekelompok()
    {
        return self::where('kode_kelompok', $this->kode_kelompok)
            ->where('id', '!=', $this->id)
            ->get();
    }

    public function isKetua(): bool
    {
        return $this->peran_kelompok === 'ketua';
    }

    public function isAnggota(): bool
    {
        return $this->peran_kelompok === 'anggota';
    }

    public function isKelompok(): bool
    {
        return $this->kategori_pemohon === 'kelompok';
    }

    public function sudahDisetujui(): bool
    {
        return $this->status_pendaftaran === 'diterima';
    }
}