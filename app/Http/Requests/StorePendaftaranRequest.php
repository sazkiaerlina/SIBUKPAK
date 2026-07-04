<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Wajib login untuk isi form ini (akun dibuat saat register, bukan di sini)
        return Auth::check();
    }

    public function rules(): array
    {
        $isKelompok = $this->input('kategori_pemohon') === 'kelompok';
        $isPT       = $this->input('jenis_instansi') === 'perguruan_tinggi';
        $isSMK      = $this->input('jenis_instansi') === 'smk';

        $rules = [
            // ── Informasi Permohonan ──────────────────────
            'kategori_pemohon' => ['required', Rule::in(['individu', 'kelompok'])],
            'jenis_instansi'   => ['required', Rule::in(['perguruan_tinggi', 'smk'])],

            // ── Data Peserta (ketua / pendaftar utama) ────
            // nama & email readonly di view, diambil dari akun yang sedang login.
            // Tetap divalidasi jaga-jaga kalau ada tampering, tapi unique di-ignore ke akun sendiri.
            'nama'             => ['required', 'string', 'max:255'],
            'tanggal_mulai'    => ['required', 'date'],
            'tanggal_selesai'  => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jenis_kelamin'    => ['required', Rule::in(['L', 'P'])],
            'instansi'         => ['required', 'string', 'max:255'],
            'email'            => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
            'whatsapp'         => ['required', 'string', 'max:20'],
            // password DIHAPUS — akun sudah ada sejak register, tidak perlu set ulang di sini

            // ── Berkas ─────────────────────────────────────
            'surat_pengantar'  => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'proposal'         => ['required', 'file', 'mimes:pdf', 'max:5120'],

            // ── Anggota (hanya divalidasi kalau ada) ──────
            'anggota'                        => [$isKelompok ? 'required' : 'nullable', 'array', 'min:1'],
            'anggota.*.nama'                 => ['required_with:anggota', 'string', 'max:255'],
            'anggota.*.jenis_kelamin'        => ['nullable', Rule::in(['L', 'P'])],
            'anggota.*.instansi'             => ['required_with:anggota', 'string', 'max:255'],
            // jenis_instansi anggota sekarang ikut nilai global (dikirim via hidden input),
            // tetap divalidasi supaya konsisten
            'anggota.*.jenis_instansi'       => ['required_with:anggota', Rule::in(['perguruan_tinggi', 'smk'])],
            'anggota.*.email'                => ['required_with:anggota', 'email', 'distinct', 'max:255', 'unique:users,email'],
            'anggota.*.whatsapp'             => ['required_with:anggota', 'string', 'max:20'],
            'anggota.*.nim'                  => ['required_with:anggota', 'string', 'max:30'],
            'anggota.*.fakultas'             => ['nullable', 'string', 'max:255'],
            'anggota.*.prodi'                => ['nullable', 'string', 'max:255'],
            'anggota.*.jurusan'              => ['nullable', 'string', 'max:255'],
            'anggota.*.kelas'                => ['nullable', 'string', 'max:5'],
            'anggota.*.kompetensi_keahlian'  => ['nullable', 'string', 'max:255'],
        ];

        if ($isPT) {
            $rules['nim']      = ['required', 'string', 'max:30'];
            $rules['fakultas'] = ['required', 'string', 'max:255'];
            $rules['prodi']    = ['required', 'string', 'max:255'];
            $rules['jurusan']  = ['required', 'string', 'max:255'];
        }

        if ($isSMK) {
            $rules['nim']                 = ['required', 'string', 'max:30'];
            $rules['kelas']               = ['required', Rule::in(['X', 'XI', 'XII'])];
            $rules['kompetensi_keahlian'] = ['required', 'string', 'max:255'];
            $rules['jurusan']             = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'anggota.required' => 'Tambahkan minimal 1 anggota untuk pendaftaran kelompok.',
            'surat_pengantar.mimes' => 'Surat pengantar harus berformat PDF.',
            'proposal.mimes' => 'Proposal harus berformat PDF.',
            'surat_pengantar.max' => 'Ukuran surat pengantar maksimal 5 MB.',
            'proposal.max' => 'Ukuran proposal maksimal 5 MB.',
        ];
    }
}