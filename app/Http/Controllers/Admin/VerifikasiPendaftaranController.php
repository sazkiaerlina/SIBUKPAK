<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifikasiPendaftaranController extends Controller
{
    /**
     * Daftar semua pendaftaran yang menunggu verifikasi.
     * Kalau kelompok, cukup tampilkan baris milik ketua saja (biar tidak dobel).
     */


    public function index(Request $request) 
    {
        $keyword = $request->keyword;
        $pendaftaran = Mahasiswa::with('user') 
        ->where('peran_kelompok', '!=', 'anggota') 
        ->when($keyword, function ($query) use ($keyword) {

        $query ->WhereHas('user', function ($q) use ($keyword) { 
            $q->where('name', 'like', "%{$keyword}%"); });

            }) 
            ->orderBy('created_at', 'desc') 
            ->paginate(10) 
            ->withQueryString();

            return view('admin.verifikasi.index', compact('pendaftaran', 'keyword')); }

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

    $this->updateStatusSekelompok(
        $mahasiswa,
        'ditolak',
        false,
        $request->catatan
    );

    return back()->with('success', 'Pendaftaran ditolak.');
}

            private function updateStatusSekelompok(
            Mahasiswa $mahasiswa,
            string $status,
            bool $isActive,
            ?string $catatan = null
            ): void

    {
        $query = $mahasiswa->kode_kelompok
            ? Mahasiswa::where('kode_kelompok', $mahasiswa->kode_kelompok)
            : Mahasiswa::where('id', $mahasiswa->id);

        $daftarMahasiswa = $query->get();

       foreach ($daftarMahasiswa as $item) {

    $item->update([
        'status_pendaftaran' => $status,
        'catatan_penolakan' => $status === 'ditolak'
            ? $catatan
            : null,
    ]);

    $item->user->update([
        'is_active' => $isActive
    ]);
        }
        
    }

    public function showSuratPengantar(Mahasiswa $mahasiswa)
    {
        abort_unless($mahasiswa->surat_pengantar_path, 404, 'Surat pengantar belum diunggah.');
        abort_unless(
            Storage::disk('public')->exists($mahasiswa->surat_pengantar_path),
            404,
            'File surat pengantar tidak ditemukan.'
        );

        return response()->file(
            Storage::disk('public')->path($mahasiswa->surat_pengantar_path),
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="surat-pengantar-' . $mahasiswa->nim . '.pdf"',
            ]
        );
    }

    public function showProposal(Mahasiswa $mahasiswa)
    {
        abort_unless($mahasiswa->proposal_path, 404, 'Proposal belum diunggah.');
        abort_unless(
            Storage::disk('public')->exists($mahasiswa->proposal_path),
            404,
            'File proposal tidak ditemukan.'
        );

        return response()->file(
            Storage::disk('public')->path($mahasiswa->proposal_path),
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="proposal-' . $mahasiswa->nim . '.pdf"',
            ]
        );
    }
}