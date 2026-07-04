<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class VerifikasiPendaftaranController extends Controller
{
    /**
     * Daftar semua pendaftaran yang menunggu verifikasi.
     * Kalau kelompok, cukup tampilkan baris milik ketua saja (biar tidak dobel).
     */
    public function index()
    {
        $pendaftaran = Mahasiswa::with('user')
            ->where('peran_kelompok', '!=', 'anggota')
            ->latest()
            ->paginate(20);

        return view('admin.verifikasi.index', compact('pendaftaran'));
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $anggota = $mahasiswa->isKelompok() ? $mahasiswa->temanSekelompok() : collect();

        return view('admin.verifikasi.show', compact('mahasiswa', 'anggota'));
    }

    /**
     * Setujui pendaftaran. Kalau kelompok, semua anggota ikut aktif otomatis.
     */
    public function approve(Mahasiswa $mahasiswa)
    {
        $this->updateStatusSekelompok($mahasiswa, 'diterima', isActive: true);

        return back()->with('success', 'Pendaftaran disetujui. Semua akun terkait sudah aktif.');
    }

    /**
     * Tolak pendaftaran. Kalau kelompok, semua anggota ikut ditolak.
     */
    public function reject(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $this->updateStatusSekelompok($mahasiswa, 'ditolak', isActive: false);

        return back()->with('success', 'Pendaftaran ditolak.');
    }

    private function updateStatusSekelompok(Mahasiswa $mahasiswa, string $status, bool $isActive): void
    {
        // Ambil semua baris dengan kode_kelompok yang sama (kalau individu, cuma 1 baris)
        $query = $mahasiswa->kode_kelompok
            ? Mahasiswa::where('kode_kelompok', $mahasiswa->kode_kelompok)
            : Mahasiswa::where('id', $mahasiswa->id);

        $daftarMahasiswa = $query->get();

        foreach ($daftarMahasiswa as $item) {
            $item->update(['status_pendaftaran' => $status]);
            $item->user->update(['is_active' => $isActive]);
        }
    }
}