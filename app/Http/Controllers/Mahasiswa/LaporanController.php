<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
     */
    

    public function downloadSertifikat()
{
    $mahasiswa = Auth::user()->mahasiswa;

    abort_unless($mahasiswa->sertifikat_path, 404, 'Sertifikat belum tersedia.');

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


}