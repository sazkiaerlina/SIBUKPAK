<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\SertifikatSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class SertifikatService
{
    /**
     * Generate PDF sertifikat untuk 1 mahasiswa.
     * Data yang ditampilkan: nama, instansi, tanggal magang, nomor surat.
     * Nomor surat diambil dari tabel `certificates` (per user).
     */
    public static function generate(Mahasiswa $mahasiswa)
    {
        $certificate = $mahasiswa->user->certificate;

        abort_unless(
            $certificate && filled($certificate->nomor_surat),
            403,
            'Sertifikat belum tersedia — nomor surat belum diisi admin.'
        );

        $data = [
            'nama'        => $mahasiswa->user->name,
            'instansi'    => $mahasiswa->universitas,
            'mulai'       => $mahasiswa->tanggal_mulai->translatedFormat('d F Y'),
            'selesai'     => $mahasiswa->tanggal_selesai->translatedFormat('d F Y'),
            'nomor_surat' => $certificate->nomor_surat,
            'setting'     => SertifikatSetting::current(),
        ];

        return Pdf::loadView('sertifikat_pdf', $data)->setPaper('a4', 'landscape');
    }

    public static function namaFile(Mahasiswa $mahasiswa): string
    {
        return 'Sertifikat-' . Str::slug($mahasiswa->user->name) . '.pdf';
    }
}