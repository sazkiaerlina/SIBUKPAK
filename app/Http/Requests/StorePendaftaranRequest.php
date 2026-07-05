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
            'nama'             => ['required', 'string', 'max:255'],
            // Tanggal mulai WAJIB setelah hari ini (tidak boleh hari ini/masa lalu)
            'tanggal_mulai'    => ['required', 'date', 'after:today'],
            'tanggal_selesai'  => ['required', 'date', 'after:tanggal_mulai'],
            'jenis_kelamin'    => ['required', Rule::in(['L', 'P'])],
            'instansi'         => ['required', 'string', 'max:255'],
            'email'            => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
            'whatsapp'         => ['required', 'string', 'max:20'],

            // ── Berkas ─────────────────────────────────────
            'surat_pengantar'  => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'proposal'         => ['required', 'file', 'mimes:pdf', 'max:5120'],

            // ── Anggota (hanya divalidasi kalau ada) ──────
            'anggota'                        => [$isKelompok ? 'required' : 'nullable', 'array', 'min:1'],
            'anggota.*.nama'                 => ['required_with:anggota', 'string', 'max:255'],
            'anggota.*.jenis_kelamin'        => ['nullable', Rule::in(['L', 'P'])],
            'anggota.*.instansi'             => ['required_with:anggota', 'string', 'max:255'],
            'anggota.*.jenis_instansi'       => ['required_with:anggota', Rule::in(['perguruan_tinggi', 'smk'])],
            'anggota.*.email'                => ['required_with:anggota', 'email', 'distinct', 'max:255', 'unique:users,email'],
            'anggota.*.whatsapp'             => ['required_with:anggota', 'string', 'max:20'],
            // NIM anggota: wajib unik terhadap tabel mahasiswas (cegah duplikat sebelum insert)
            // DAN distinct (tidak boleh sama antar-anggota dalam 1 form submit yang sama).
            'anggota.*.nim'                  => ['required_with:anggota', 'string', 'max:30', 'distinct', Rule::unique('mahasiswas', 'nim')],
            'anggota.*.fakultas'             => ['nullable', 'string', 'max:255'],
            'anggota.*.prodi'                => ['nullable', 'string', 'max:255'],
            'anggota.*.jurusan'              => ['nullable', 'string', 'max:255'],
            'anggota.*.kelas'                => ['nullable', 'string', 'max:5'],
            'anggota.*.kompetensi_keahlian'  => ['nullable', 'string', 'max:255'],
        ];

        if ($isPT) {
            // NIM ketua: wajib unik terhadap tabel mahasiswas
            $rules['nim']      = ['required', 'string', 'max:30', Rule::unique('mahasiswas', 'nim')];
            $rules['fakultas'] = ['required', 'string', 'max:255'];
            $rules['prodi']    = ['required', 'string', 'max:255'];
            $rules['jurusan']  = ['required', 'string', 'max:255'];
        }

        if ($isSMK) {
            $rules['nim']                 = ['required', 'string', 'max:30', Rule::unique('mahasiswas', 'nim')];
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
            'tanggal_mulai.after' => 'Tanggal mulai magang harus setelah hari ini.',
            'nim.unique' => 'NIM/NISN ini sudah terdaftar sebelumnya. Periksa kembali atau hubungi admin jika ini kesalahan.',
            'anggota.*.nim.unique' => 'NIM/NISN salah satu anggota sudah terdaftar sebelumnya.',
            'anggota.*.nim.distinct' => 'NIM/NISN antar anggota tidak boleh sama.',
        ];
    }
}