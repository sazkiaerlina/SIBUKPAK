<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\SertifikatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        return view('mahasiswa.laporan', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'laporan' => ['required', 'file', 'mimes:pdf', 'max:10240'], // 10 MB
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $path = $request->file('laporan')->store('laporan-magang', 'public');

        $mahasiswa->update([
            'laporan_path'        => $path,
            'laporan_uploaded_at' => now(),
        ]);

        return back()->with('success', 'Laporan berhasil diunggah.');
    }

    /**
     * Mahasiswa mengunduh sertifikatnya sendiri.
     * SertifikatService yang menentukan boleh/tidaknya (cek nomor_surat
     * sudah diisi admin atau belum) — controller ini tidak perlu cek ulang.
     */
    public function downloadSertifikat()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $pdf = SertifikatService::generate($mahasiswa);

        return $pdf->download(SertifikatService::namaFile($mahasiswa));
    }
}