<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Mahasiswa;
use App\Services\SertifikatService;
use Illuminate\Http\Request;

class LaporanSertifikatController extends Controller
{
    /** Daftar semua mahasiswa yang sudah mengunggah laporan */
    public function index()
    {
        // Hapus 'user.certificate', cukup panggil 'user' saja
        $daftar = Mahasiswa::with(['user']) 
            ->whereNotNull('laporan_path')
            ->latest('laporan_uploaded_at')
            ->get();

        return view('admin.laporan-sertifikat.index', compact('daftar'));
    }

    /**
     * Simpan nomor surat ke tabel certificates.
     * Mengisi nomor surat = otomatis membuka akses download sertifikat mahasiswa.
     */
    public function simpanSertifikat(Request $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:100'],
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
        ]);

        Certificate::updateOrCreate(
            ['user_id' => $mahasiswa->user_id],
            $data
        );

        return back()->with('success', 'Nomor surat tersimpan. Mahasiswa kini bisa mengunduh sertifikatnya.');
    }

    /** Admin mengunduh/preview sertifikat */
    public function downloadSertifikat(Mahasiswa $mahasiswa)
    {
        $pdf = SertifikatService::generate($mahasiswa);

        return $pdf->download(SertifikatService::namaFile($mahasiswa));
    }
}