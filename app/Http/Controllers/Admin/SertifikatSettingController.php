<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SertifikatSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikatSettingController extends Controller
{
    public function edit()
    {
        $setting = SertifikatSetting::current();

        return view('admin.sertifikat-setting.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'background'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // 5MB
            'nama_top'        => ['required', 'numeric', 'min:0', 'max:100'],
            'nama_left'       => ['required', 'numeric', 'min:0', 'max:100'],
            'nama_font_size'  => ['required', 'integer', 'min:8', 'max:100'],
            'nama_color'      => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'nomor_top'       => ['required', 'numeric', 'min:0', 'max:100'],
            'nomor_left'      => ['required', 'numeric', 'min:0', 'max:100'],
            'nomor_font_size' => ['required', 'integer', 'min:6', 'max:50'],
            'nomor_color'     => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $setting = SertifikatSetting::current();

        // Ganti background kalau ada file baru diunggah
        if ($request->hasFile('background')) {
            // Hapus background lama supaya storage tidak menumpuk file tak terpakai
            if ($setting->background_path) {
                Storage::disk('public')->delete($setting->background_path);
            }
            $data['background_path'] = $request->file('background')->store('sertifikat-template', 'public');
        }
        unset($data['background']);

        $setting->update($data);

        return back()->with('success', 'Pengaturan sertifikat tersimpan. Cek hasilnya lewat tombol Preview.');
    }

    /** Tampilkan PDF contoh langsung di browser (bukan download) pakai data dummy */
    public function preview()
    {
        $setting = SertifikatSetting::current();

        $data = [
            'nama'        => 'Nama Mahasiswa Contoh',
            'instansi'    => 'Universitas/Sekolah Contoh',
            'mulai'       => now()->subMonths(2)->translatedFormat('d F Y'),
            'selesai'     => now()->translatedFormat('d F Y'),
            'nomor_surat' => '001/CONTOH/VII/2026',
            'setting'     => $setting,
        ];

        $pdf = Pdf::loadView('sertifikat_pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->stream('preview-sertifikat.pdf');
    }
}