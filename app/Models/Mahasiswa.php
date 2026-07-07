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
        'catatan_penolakan',
        'surat_pengantar_path',
        'proposal_path',
        // ── Ditambahkan: kolom laporan & sertifikat (sebelumnya belum fillable) ──
        'laporan_path',
        'laporan_uploaded_at',
        'sertifikat_path',
        
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'       => 'date',
            'tanggal_selesai'     => 'date',
            'laporan_uploaded_at' => 'datetime',
        ];
    }

    // ══════════════════════════════════════════════════════════
    //  RELASI
    // ══════════════════════════════════════════════════════════

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke semua presensi milik mahasiswa ini.
     * WAJIB ADA — dipakai oleh statistikBulan(), presensiHariIni(),
     * dan semua controller di app/Http/Controllers/Mahasiswa/.
     */
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    // ══════════════════════════════════════════════════════════
    //  HELPER KELOMPOK
    // ══════════════════════════════════════════════════════════

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

    // ══════════════════════════════════════════════════════════
    //  HELPER PRESENSI
    // ══════════════════════════════════════════════════════════

    /**
     * Ambil record presensi untuk HARI INI (jika ada).
     * WAJIB ADA — dipakai di DashboardController dan PresensiController.
     */
    public function presensiHariIni()
    {
        return $this->presensis()
            ->whereDate('tanggal', now()->toDateString())
            ->first();
    }

    /**
     * Hitung statistik kehadiran (hadir/sakit/izin/alpa) untuk bulan & tahun tertentu.
     */
    public function statistikBulan(int $tahun, int $bulan): array
    {
        $rekap = $this->presensis()
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return array_merge(
            ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'terlambat' => 0],
            $rekap
        );
    }

    public function hitungStatusMagang(): string
    {
        $today = now()->toDateString();

        if ($today < $this->tanggal_mulai->toDateString()) {
            return 'belum_mulai';
        }

        if ($today > $this->tanggal_selesai->toDateString()) {
            return 'selesai';
        }

        return 'berlangsung';
    }
}