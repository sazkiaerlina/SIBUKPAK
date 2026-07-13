<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
 * Mengunggah file sertifikat mahasiswa.
 * File yang diunggah akan langsung tersedia untuk diunduh oleh mahasiswa.
 */
    

public function simpanSertifikat(Request $request, Mahasiswa $mahasiswa)
{
    $request->validate([
        'sertifikat' => 'required|mimes:pdf|max:10240',
    ]);

    if ($mahasiswa->sertifikat_path) {
        Storage::disk('public')->delete($mahasiswa->sertifikat_path);
    }

   
$namaFile = 'Sertifikat-' .
    Str::slug($mahasiswa->user->name) .
     '-'.
     $mahasiswa->nim .
    '.pdf';

$path = $request->file('sertifikat')->storeAs(
    'sertifikat',
    $namaFile,
    'public'
);


    $mahasiswa->update([
        'sertifikat_path' => $path,
    ]);

    return back()->with('success', 'Sertifikat berhasil diupload.');
}


    // batas
public function downloadSertifikat(Mahasiswa $mahasiswa)
{
    abort_unless($mahasiswa->sertifikat_path, 404);

    return response()->download(
        storage_path('app/public/' . $mahasiswa->sertifikat_path),
        'Sertifikat-' . $mahasiswa->user->name . '-' . $mahasiswa->nim . '.pdf',
        [
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]
    );
}

/**
 * Admin melihat file laporan yang diunggah mahasiswa,
 * LANGSUNG lewat Laravel (tidak bergantung symlink public/storage).
 */
public function showLaporan(Mahasiswa $mahasiswa)
{
    abort_unless($mahasiswa->laporan_path, 404, 'Laporan belum diunggah.');
    abort_unless(Storage::disk('public')->exists($mahasiswa->laporan_path), 404, 'File laporan tidak ditemukan.');

    return response()->file(
        Storage::disk('public')->path($mahasiswa->laporan_path),
        [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-' . $mahasiswa->nim . '.pdf"',
        ]
    );
}


}