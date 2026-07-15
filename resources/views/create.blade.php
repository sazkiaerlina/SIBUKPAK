@extends('layouts.app')

@section('content')

<div
    x-data="{
        kategori: '{{ old('kategori_pemohon', '') }}',
        jenisInstansi: '{{ old('jenis_instansi', '') }}',
        anggota: [],

        tambahAnggota() {
            this.anggota.push({
                nama: '', email: '', nim: '', whatsapp: '',
                jenis_kelamin: '', instansi: '',
                fakultas: '', prodi: '', jurusan: '',
                kelas: '', kompetensi_keahlian: ''
            });
        },

        hapusAnggota(index) {
            this.anggota.splice(index, 1);
        }
    }"
    class="min-h-screen bg-[#D3E2F8] py-10 px-4"
>
    <div class="max-w-6xl mx-auto">

        
        {{-- ── Bar Atas: Tombol Logout (kiri) & Info Akun (kanan) ── --}}
        <div class="flex items-center justify-between mb-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="flex items-center gap-2 bg-white border border-gray-300
                           text-gray-600 text-sm font-semibold px-4 py-2 rounded-xl
                           shadow-sm hover:bg-red-50 hover:border-red-300 hover:text-red-600
                           transition"
                >
                    <span class="text-base">←</span>
                    Keluar
                </button>
            </form>
        </div>


        {{-- ── Judul ──────────────────────────────────── --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-[#043277] tracking-tight">PENDAFTARAN MAGANG</h1>
            <p class="text-gray-500 text-sm mt-2">BPS Kabupaten Ogan Ilir — PANDU</p>
        </div>

        {{-- ── Error Global ────────────────────────────── --}}
        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-300 rounded-2xl p-4">
            <p class="font-semibold text-red-700 text-sm mb-2">❌ Harap perbaiki kesalahan berikut:</p>
            <ul class="text-red-600 text-sm space-y-1 list-disc ml-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form
            {{-- action="{{ route('daftar.store') }}" --}}
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf

            {{-- ════════════════════════════════════════════
                 SEKSI 1: INFORMASI PERMOHONAN
                 Berisi 2 dropdown: Kategori Pemohon & Jenis Instansi.
            ════════════════════════════════════════════ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
                <div class="bg-[#043277] px-6 py-4">
                    <h2 class="text-white font-bold text-base">Informasi Permohonan</h2>
                </div>
                <div class="p-6 space-y-4">

                    {{-- Kategori Pemohon --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Kategori Pemohon <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="kategori_pemohon"
                            x-model="kategori"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                   focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                        >
                            <option value="">-- Pilih Kategori --</option>
                            <option value="individu" {{ old('kategori_pemohon') === 'individu' ? 'selected' : '' }}>
                                Individu
                            </option>
                            <option value="kelompok" {{ old('kategori_pemohon') === 'kelompok' ? 'selected' : '' }}>
                                Kelompok
                            </option>
                        </select>
                        @error('kategori_pemohon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Info otomatis muncul jika kelompok dipilih --}}
                    <div
                        x-show="kategori === 'kelompok'"
                        x-transition
                        x-cloak
                        class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3
                               flex items-start gap-3"
                    >
                        <span class="text-blue-500 text-lg mt-0.5">ℹ️</span>
                        <div>
                            <p class="text-sm font-semibold text-[#043277]">
                                Anda mendaftar sebagai Ketua Kelompok
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Kode kelompok akan dibuat otomatis oleh sistem setelah form dikirim.
                                Tambahkan data anggota di bagian bawah.
                            </p>
                        </div>
                    </div>

                    {{-- Jenis Instansi (dropdown) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Jenis Instansi <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="jenis_instansi"
                            x-model="jenisInstansi"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                   focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                        >
                            <option value="">-- Pilih Jenis Instansi --</option>
                            <option value="perguruan_tinggi" {{ old('jenis_instansi') === 'perguruan_tinggi' ? 'selected' : '' }}>
                                🎓 Perguruan Tinggi (Universitas / S1 / D3 / D4)
                            </option>
                            <option value="smk" {{ old('jenis_instansi') === 'smk' ? 'selected' : '' }}>
                                🏫 SMK (Sekolah Menengah Kejuruan)
                            </option>
                        </select>
                        @error('jenis_instansi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            {{-- ════════════════════════════════════════════
                 SEKSI 2: DATA PESERTA
                 Muncul setelah kategori & jenis instansi dipilih.
                 Field menyesuaikan otomatis dengan jenisInstansi.
            ════════════════════════════════════════════ --}}
            <div
                x-show="kategori !== '' && jenisInstansi !== ''"
                x-transition
                x-cloak
                class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden"
            >
                <div class="bg-[#043277] px-6 py-4">
                    <h2 class="text-white font-bold text-base">
                        Data Peserta
                        <span
                            x-show="kategori === 'kelompok'"
                            class="text-blue-300 font-normal text-sm ml-1"
                        >(Ketua Kelompok)</span>
                    </h2>
                </div>

                <div class="p-6 space-y-4">

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama"
                            value="{{ old('nama', auth()->user()->name) }}"
                            readonly
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-100 text-sm text-gray-600
                                cursor-not-allowed
                                @error('nama') border-red-400 bg-red-50 @enderror"
                        >
                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Tanggal Mulai Magang <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_mulai"
                            value="{{ old('tanggal_mulai') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                        >
                        @error('tanggal_mulai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Tanggal Selesai Magang <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_selesai"
                            value="{{ old('tanggal_selesai') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                        >
                        @error('tanggal_selesai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="jenis_kelamin"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                        >
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- ─── ASAL INSTANSI ──────────────────────────── --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Asal Instansi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="instansi"
                            value="{{ old('instansi') }}"
                            placeholder="Nama universitas / sekolah / instansi"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                        >
                        @error('instansi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- ════════════════════════════════════════════
                        FIELD PERGURUAN TINGGI
                        Muncul jika jenisInstansi = perguruan_tinggi
                        (dipilih dari dropdown di seksi Informasi Permohonan)
                    ════════════════════════════════════════════ --}}
                    <template x-if="jenisInstansi === 'perguruan_tinggi'">
                        <div class="space-y-4">
                            <p class="text-xs font-semibold text-[#043277] flex items-center gap-1">
                                🎓 Data Perguruan Tinggi
                            </p>

                            {{-- NIM --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    NIM <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nim"
                                    value="{{ old('nim') }}"
                                    placeholder="Nomor Induk Mahasiswa"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition
                                        @error('nim') border-red-400 bg-red-50 @enderror"
                                >
                                @error('nim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Fakultas --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Fakultas <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="fakultas"
                                    value="{{ old('fakultas') }}"
                                    placeholder="Contoh: Fakultas Teknik"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                                >
                                @error('fakultas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Program Studi --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Program Studi <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="prodi"
                                    value="{{ old('prodi') }}"
                                    placeholder="Contoh: S1 Teknik Informatika"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                                >
                                @error('prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jurusan --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Jurusan <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="jurusan"
                                    value="{{ old('jurusan') }}"
                                    placeholder="Contoh: Teknik Informatika"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                                >
                                @error('jurusan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </template>

                    {{-- ════════════════════════════════════════════
                        FIELD SMK
                        Muncul jika jenisInstansi = smk
                        (dipilih dari dropdown di seksi Informasi Permohonan)
                    ════════════════════════════════════════════ --}}
                    <template x-if="jenisInstansi === 'smk'">
                        <div class="space-y-4">
                            <p class="text-xs font-semibold text-orange-600 flex items-center gap-1">
                                🏫 Data SMK
                            </p>

                            {{-- NISN --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    NISN <span class="text-red-500">*</span>
                                    <span class="font-normal text-gray-400 text-xs">(Nomor Induk Siswa Nasional)</span>
                                </label>
                                <input
                                    type="text"
                                    name="nim"
                                    value="{{ old('nim') }}"
                                    placeholder="10 digit NISN"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition
                                        @error('nim') border-red-400 bg-red-50 @enderror"
                                >
                                @error('nim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Kelas --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Kelas <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="kelas"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                                >
                                    <option value="">-- Pilih Kelas --</option>
                                    <option value="X"   {{ old('kelas') === 'X'   ? 'selected' : '' }}>Kelas X (10)</option>
                                    <option value="XI"  {{ old('kelas') === 'XI'  ? 'selected' : '' }}>Kelas XI (11)</option>
                                    <option value="XII" {{ old('kelas') === 'XII' ? 'selected' : '' }}>Kelas XII (12)</option>
                                </select>
                                @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Kompetensi Keahlian --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Kompetensi Keahlian <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="kompetensi_keahlian"
                                    value="{{ old('kompetensi_keahlian') }}"
                                    placeholder="Contoh: Teknik Komputer dan Jaringan"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                                >
                                @error('kompetensi_keahlian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jurusan SMK --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Jurusan <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="jurusan"
                                    value="{{ old('jurusan') }}"
                                    placeholder="Contoh: Teknik Informatika"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                                >
                                @error('jurusan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </template>

                    {{-- ─── EMAIL ──────────────────────────────────── --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                            <span class="font-normal text-gray-400 text-xs">(sesuai akun login)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                readonly
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 bg-gray-100 text-sm text-gray-600
                                    cursor-not-allowed
                                    @error('email') border-red-400 bg-red-50 @enderror"
                            >
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- ─── WHATSAPP ────────────────────────────────── --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            No. WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="whatsapp"
                                value="{{ old('whatsapp') }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                    focus:outline-none focus:ring-2 focus:ring-[#043277] transition"
                            >
                        </div>
                        @error('whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>    
                </div>
            </div>

            {{-- ════════════════════════════════════════════
                 SEKSI 3: DATA ANGGOTA
                 Muncul HANYA jika kategori = kelompok.
                 Tiap anggota tetap punya dropdown jenis instansi
                 sendiri karena instansi tiap anggota bisa berbeda.
            ════════════════════════════════════════════ --}}
            <div
                x-show="kategori === 'kelompok' && jenisInstansi !== ''"
                x-transition
                x-cloak
            >
                {{-- Header Seksi --}}
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-lg font-black text-[#043277]">
                            Data Anggota Kelompok
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Akun login anggota dibuat otomatis. Password default = NIM anggota.
                        </p>
                    </div>
                    <button
                        type="button"
                        @click="tambahAnggota()"
                        class="flex items-center gap-2 bg-[#043277] hover:bg-blue-900
                               text-white text-sm font-semibold px-4 py-2.5 rounded-xl
                               transition active:scale-95 whitespace-nowrap shadow-sm"
                    >
                        <span>➕</span> Tambah Anggota
                    </button>
                </div>

                {{-- List Anggota Dinamis --}}
                <div class="space-y-4">

                    <template x-for="(item, index) in anggota" :key="index">
                        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">

                            {{-- Header Card Anggota --}}
                            <div class="flex items-center justify-between px-6 py-3
                                        bg-blue-50 border-b border-blue-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#043277] rounded-full flex items-center
                                                justify-center text-white text-xs font-bold"
                                         x-text="index + 1">
                                    </div>
                                    <h3 class="font-bold text-[#043277] text-sm"
                                        x-text="'Anggota ' + (index + 1)">
                                    </h3>
                                </div>
                                <button
                                    type="button"
                                    @click="hapusAnggota(index)"
                                    class="text-red-500 hover:text-red-700 text-xs font-semibold
                                           px-3 py-1 rounded-lg hover:bg-red-50 transition
                                           flex items-center gap-1"
                                >
                                    🗑️ Hapus
                                </button>
                            </div>

                            {{-- Field Anggota --}}
                            <div class="p-6 space-y-4">
                            <input type="hidden" :name="'anggota[' + index + '][jenis_instansi]'" :value="jenisInstansi">
                                {{-- Nama --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" :name="'anggota[' + index + '][nama]'" x-model="item.nama"
                                        placeholder="Nama lengkap anggota"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                </div>

                                {{-- Jenis Kelamin --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin</label>
                                    <select :name="'anggota[' + index + '][jenis_kelamin]'" x-model="item.jenis_kelamin"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                                focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                {{-- Asal Instansi --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Asal Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" :name="'anggota[' + index + '][instansi]'" x-model="item.instansi"
                                        placeholder="Nama universitas / sekolah"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                </div>

                                {{-- Field Perguruan Tinggi --}}
                                <template x-if="jenisInstansi === 'perguruan_tinggi'">                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
                                            <input type="text" :name="'anggota[' + index + '][nim]'" x-model="item.nim"
                                                placeholder="Nomor Induk Mahasiswa"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Fakultas <span class="text-red-500">*</span></label>
                                            <input type="text" :name="'anggota[' + index + '][fakultas]'" x-model="item.fakultas"
                                                placeholder="Contoh: Fakultas Teknik"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Program Studi <span class="text-red-500">*</span></label>
                                            <input type="text" :name="'anggota[' + index + '][prodi]'" x-model="item.prodi"
                                                placeholder="Contoh: S1 Teknik Informatika"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jurusan <span class="text-red-500">*</span></label>
                                            <input type="text" :name="'anggota[' + index + '][jurusan]'" x-model="item.jurusan"
                                                placeholder="Contoh: Teknik Informatika"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                        </div>
                                    </div>
                                </template>

                                {{-- Field SMK --}}
                                <template x-if="jenisInstansi === 'smk'"> 
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                                NISN <span class="text-red-500">*</span>
                                                <span class="font-normal text-gray-400 text-xs">(password default = NISN ini)</span>
                                            </label>
                                            <input type="text" :name="'anggota[' + index + '][nim]'" x-model="item.nim"
                                                placeholder="10 digit NISN"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                                            <select :name="'anggota[' + index + '][kelas]'" x-model="item.kelas"
                                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm
                                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                                <option value="">-- Pilih Kelas --</option>
                                                <option value="X">Kelas X (10)</option>
                                                <option value="XI">Kelas XI (11)</option>
                                                <option value="XII">Kelas XII (12)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kompetensi Keahlian <span class="text-red-500">*</span></label>
                                            <input type="text" :name="'anggota[' + index + '][kompetensi_keahlian]'" x-model="item.kompetensi_keahlian"
                                                placeholder="Contoh: Teknik Komputer dan Jaringan"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jurusan <span class="text-red-500">*</span></label>
                                            <input type="text" :name="'anggota[' + index + '][jurusan]'" x-model="item.jurusan"
                                                placeholder="Contoh: Teknik Informatika"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                        focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                        </div>
                                    </div>
                                </template>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" :name="'anggota[' + index + '][email]'" x-model="item.email"
                                        placeholder="email@contoh.com"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                </div>

                                {{-- WhatsApp --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        No. WhatsApp <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" :name="'anggota[' + index + '][whatsapp]'" x-model="item.whatsapp"
                                        placeholder="08xxxxxxxxxx"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                                                focus:outline-none focus:ring-2 focus:ring-[#043277] transition">
                                </div>

                            </div>
                        </div>
                    </template>

                    {{-- Placeholder jika belum ada anggota --}}
                    <div
                        x-show="anggota.length === 0"
                        class="bg-white border-2 border-dashed border-blue-200 rounded-2xl
                               p-10 text-center"
                    >
                        <p class="text-4xl mb-2">👥</p>
                        <p class="text-sm font-semibold text-gray-500">Belum ada anggota ditambahkan</p>
                        <p class="text-xs text-gray-400 mt-1">
                            Klik tombol <strong>"➕ Tambah Anggota"</strong> di atas untuk mulai
                        </p>
                    </div>

                </div>
            </div>

            {{-- ════════════════════════════════════════════
                 SEKSI 4: UPLOAD BERKAS
                 Muncul setelah kategori & jenis instansi dipilih.
            ════════════════════════════════════════════ --}}
            <div
                x-show="kategori !== '' && jenisInstansi !== ''"
                x-transition
                x-cloak
                class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden"
            >
                <div class="bg-[#043277] px-6 py-4">
                    <h2 class="text-white font-bold text-base">Upload Berkas</h2>
                </div>
                <div class="p-6 space-y-6">

                    {{-- Surat Pengantar --}}
                    <div x-data="{ fileName: '' }">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Surat Pengantar <span class="text-red-500">*</span>
                        </label>
                        <label
                            class="flex flex-col items-center justify-center w-full h-36 border-2
                                   border-dashed border-gray-300 rounded-2xl cursor-pointer
                                   hover:border-[#043277] hover:bg-blue-50 transition"
                        >
                            <template x-if="!fileName">
                                <div class="flex flex-col items-center pointer-events-none">
                                    <svg class="w-10 h-10 text-[#043277] mb-2" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm text-gray-600 font-medium">Klik untuk memilih file</p>
                                    <p class="text-xs text-gray-400 mt-1">Format PDF • Maksimal 5 MB</p>
                                </div>
                            </template>
                            <template x-if="fileName">
                                <div class="flex flex-col items-center pointer-events-none">
                                    <span class="text-3xl mb-1">📄</span>
                                    <p class="text-sm text-green-700 font-semibold" x-text="fileName"></p>
                                    <p class="text-xs text-gray-400 mt-1">Klik untuk ganti file</p>
                                </div>
                            </template>
                            <input
                                type="file"
                                name="surat_pengantar"
                                accept=".pdf"
                                class="hidden"
                                @change="fileName = $event.target.files[0]?.name ?? ''"
                            >
                        </label>
                        @error('surat_pengantar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Proposal Magang --}}
                    <div x-data="{ fileName: '' }">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Proposal Magang <span class="text-red-500">*</span>
                        </label>
                        <label
                            class="flex flex-col items-center justify-center w-full h-36 border-2
                                   border-dashed border-gray-300 rounded-2xl cursor-pointer
                                   hover:border-[#043277] hover:bg-blue-50 transition"
                        >
                            <template x-if="!fileName">
                                <div class="flex flex-col items-center pointer-events-none">
                                    <svg class="w-10 h-10 text-[#043277] mb-2" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm text-gray-600 font-medium">Klik untuk memilih file</p>
                                    <p class="text-xs text-gray-400 mt-1">Format PDF • Maksimal 5 MB</p>
                                </div>
                            </template>
                            <template x-if="fileName">
                                <div class="flex flex-col items-center pointer-events-none">
                                    <span class="text-3xl mb-1">📄</span>
                                    <p class="text-sm text-green-700 font-semibold" x-text="fileName"></p>
                                    <p class="text-xs text-gray-400 mt-1">Klik untuk ganti file</p>
                                </div>
                            </template>
                            <input
                                type="file"
                                name="proposal"
                                accept=".pdf"
                                class="hidden"
                                @change="fileName = $event.target.files[0]?.name ?? ''"
                            >
                        </label>
                        @error('proposal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <p class="font-semibold text-[#043277] text-sm mb-2">ℹ️ Informasi</p>
                        <ul class="text-sm text-gray-600 space-y-1.5 list-disc ml-4">
                            <li>
                                Surat pengantar dan proposal diunggah oleh
                                <strong>pendaftar utama</strong> (berlaku untuk seluruh kelompok).
                            </li>
                            <li x-show="kategori === 'kelompok'">
                                Akun anggota dibuat otomatis — password default =
                                <strong>NIM masing-masing</strong>.
                            </li>
                            <li>
                                Berkas harus berformat <strong>PDF</strong>,
                                maksimal <strong>5 MB</strong> per file.
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            {{-- ════════════════════════════════════════════
                 TOMBOL SUBMIT — Sticky Bottom
            ════════════════════════════════════════════ --}}
            <div
                x-show="kategori !== '' && jenisInstansi !== ''"
                x-transition
                x-cloak
                class="bottom-0 px-6 py-4 pb-8"
            >

                <button
                    type="submit"
                    class="w-full py-4 bg-[#043277] text-white font-black text-base rounded-xl
                           hover:hover:scale-105 active:scale-95 transition-all duration-150"
                >
                    Kirim Pendaftaran 
                </button>
            </div>

        </form>
    </div>
</div>

@endsection