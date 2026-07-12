<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PanduanController extends Controller
{
    /** Nama file selalu tetap, supaya link di navbar tidak pernah berubah */
    private const PATH_MAHASISWA = 'panduan/panduan-mahasiswa.pdf';
    private const PATH_ADMIN     = 'panduan/panduan-admin.pdf';

    public function edit()
    {
        $adaMahasiswa = Storage::disk('public')->exists(self::PATH_MAHASISWA);
        $adaAdmin     = Storage::disk('public')->exists(self::PATH_ADMIN);

        return view('admin.panduan.edit', compact('adaMahasiswa', 'adaAdmin'));
    }

    /** Upload / ganti PDF panduan untuk mahasiswa */
    public function uploadMahasiswa(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'], // 20 MB
        ], [
            'file.mimes' => 'File harus berformat PDF.',
            'file.max'   => 'Ukuran file maksimal 20 MB.',
        ]);

        Storage::disk('public')->putFileAs(
            'panduan',
            $request->file('file'),
            'panduan-mahasiswa.pdf'
        );

        return back()->with('success', 'Buku panduan mahasiswa berhasil diperbarui.');
    }

    /** Upload / ganti PDF panduan untuk admin */
    public function uploadAdmin(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ], [
            'file.mimes' => 'File harus berformat PDF.',
            'file.max'   => 'Ukuran file maksimal 20 MB.',
        ]);

        Storage::disk('public')->putFileAs(
            'panduan',
            $request->file('file'),
            'panduan-admin.pdf'
        );

        return back()->with('success', 'Buku panduan admin berhasil diperbarui.');
    }
}