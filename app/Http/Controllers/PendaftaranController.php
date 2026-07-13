<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePendaftaranRequest;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    public function create()
    {
        return view('create');
    }

    /**
     * Melengkapi profil Mahasiswa untuk user yang SUDAH login.
     * Anggota kelompok tetap dibuatkan akun baru di sini (mereka belum register sendiri).
     */
    public function store(StorePendaftaranRequest $request)
    {
        $data = $request->validated();
        $isKelompok = $data['kategori_pemohon'] === 'kelompok';
        $ketua = $request->user(); // akun ketua yang sedang login, BUKAN dibuat baru

        $mahasiswaKetua = DB::transaction(function () use ($request, $data, $isKelompok, $ketua) {

            // ── 1. Upload berkas ─────────────────────────────
            $suratPengantarPath = $request->file('surat_pengantar')
                ->store('pendaftaran/surat-pengantar', 'public');

            $proposalPath = $request->file('proposal')
                ->store('pendaftaran/proposal', 'public');

            // ── 2. Kode kelompok ──────────────────────────────
            $kodeKelompok = $isKelompok ? $this->generateKodeKelompok() : null;

            // ── 3. Update data akun ketua (bukan bikin baru) ──
            $ketua->update([
                'name'             => $data['nama'],
                'is_uploaded_form' => true,
            ]);

            $mahasiswaKetua = Mahasiswa::create([
                'user_id'              => $ketua->id,
                'kategori_pemohon'     => $data['kategori_pemohon'],
                'jenis_instansi'       => $data['jenis_instansi'],
                'peran_kelompok'       => $isKelompok ? 'ketua' : 'individu',
                'nim'                  => $data['nim'] ?? null,
                'jurusan'              => $data['jurusan'] ?? null,
                'prodi'                => $data['prodi'] ?? null,
                'fakultas'             => $data['fakultas'] ?? null,
                'universitas'          => $data['instansi'],
                'nomor_hp'             => $data['whatsapp'],
                'jenis_kelamin'        => $data['jenis_kelamin'],
                'kelas'                => $data['kelas'] ?? null,
                'kompetensi_keahlian'  => $data['kompetensi_keahlian'] ?? null,
                'tanggal_mulai'        => $data['tanggal_mulai'],
                'tanggal_selesai'      => $data['tanggal_selesai'],
                'kode_kelompok'        => $kodeKelompok,
                'status_magang'        => null,
                'status_pendaftaran'   => 'pending',
                'surat_pengantar_path' => $suratPengantarPath,
                'proposal_path'        => $proposalPath,
            ]);

            // ── 4. Anggota kelompok — mereka BELUM punya akun, jadi tetap dibuatkan ──
            if ($isKelompok && !empty($data['anggota'])) {
                foreach ($data['anggota'] as $item) {
                    $userAnggota = User::create([
                        'name'             => $item['nama'],
                        'email'            => $item['email'],
                        'password'         => Hash::make($item['nim']), // default = NIM/NISN
                        'role'             => 'mahasiswa',
                        'is_active'        => false,
                        'is_uploaded_form' => true,
                    ]);

                    Mahasiswa::create([
                        'user_id'              => $userAnggota->id,
                        'kategori_pemohon'     => 'kelompok',
                        'jenis_instansi'       => $item['jenis_instansi'],
                        'peran_kelompok'       => 'anggota',
                        'nim'                  => $item['nim'] ?? null,
                        'jurusan'              => $item['jurusan'] ?? null,
                        'prodi'                => $item['prodi'] ?? null,
                        'fakultas'             => $item['fakultas'] ?? null,
                        'universitas'          => $item['instansi'],
                        'nomor_hp'             => $item['whatsapp'],
                        'jenis_kelamin'        => $item['jenis_kelamin'] ?? null,
                        'kelas'                => $item['kelas'] ?? null,
                        'kompetensi_keahlian'  => $item['kompetensi_keahlian'] ?? null,
                        'tanggal_mulai'        => $data['tanggal_mulai'],
                        'tanggal_selesai'      => $data['tanggal_selesai'],
                        'kode_kelompok'        => $kodeKelompok,
                        'status_magang'        => null,
                        'status_pendaftaran'   => 'pending',
                        'surat_pengantar_path' => $suratPengantarPath,
                        'proposal_path'        => $proposalPath,
                    ]);
                }
            }

            return $mahasiswaKetua;
        });

        return redirect()
            ->route('daftar.sukses', $mahasiswaKetua->id)
            ->with('success', 'Pendaftaran berhasil dikirim. Akun akan aktif setelah admin memverifikasi.');
    }

    private function generateKodeKelompok(): string
    {
        do {
            $kode = 'KLP-' . strtoupper(Str::random(6));
        } while (Mahasiswa::where('kode_kelompok', $kode)->exists());

        return $kode;
    }

    public function riwayat(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user');

        // Kalau kelompok, ambil juga data anggota lain dengan kode_kelompok yang sama
        $anggotaLain = null;
        if ($mahasiswa->kategori_pemohon === 'kelompok' && $mahasiswa->kode_kelompok) {
            $anggotaLain = Mahasiswa::where('kode_kelompok', $mahasiswa->kode_kelompok)
                ->where('id', '!=', $mahasiswa->id)
                ->with('user')
                ->get();
        }

        return view('riwayat', compact('mahasiswa', 'anggotaLain'));
    }

     public function showSuratPengantar(Mahasiswa $mahasiswa)
    {
        $this->authorizeAkses($mahasiswa);

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

    /**
     * Tampilkan file Proposal Magang milik mahasiswa yang login.
     */
    public function showProposal(Mahasiswa $mahasiswa)
    {
        $this->authorizeAkses($mahasiswa);

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


    private function authorizeAkses(Mahasiswa $mahasiswa): void
    {
        abort_unless(
            Auth::id() === $mahasiswa->user_id || Auth::user()->is_admin ?? false,
            403,
            'Anda tidak berhak mengakses file ini.'
        );
    }
}