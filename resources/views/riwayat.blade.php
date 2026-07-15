@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#D3E2F8] py-10 px-4">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- ── Header + Tombol Logout ──────────────────── --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-[#043277] tracking-tight">Riwayat Pendaftaran</h1>
                <p class="text-gray-500 text-sm mt-1">BPS Kabupaten Ogan Ilir — PANDU</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="flex items-center gap-2 bg-white border border-red-200 text-red-600
                           hover:bg-red-50 text-sm font-semibold px-4 py-2.5 rounded-xl
                           transition active:scale-95 shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>

        {{-- ── Flash Message Sukses ────────────────────── --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-300 rounded-2xl p-4 flex items-start gap-3">
                <span class="text-green-500 text-lg mt-0.5">✅</span>
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ── Kartu Status Utama ───────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
            <div class="bg-[#043277] px-6 py-4 flex items-center justify-between">
                <h2 class="text-white font-bold text-base">Status Pendaftaran</h2>
                @if($mahasiswa->kategori_pemohon === 'kelompok')
                    <span class="bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full">
                        Kode: {{ $mahasiswa->kode_kelompok }}
                    </span>
                @endif
            </div>

            <div class="p-6 space-y-6">

                {{-- Timeline Status --}}
                @php
                    $status = $mahasiswa->status_pendaftaran; // pending | diterima | ditolak
                    $akunAktif = $mahasiswa->user->is_active;
                    // true kalau sudah pernah diterima TAPI akunnya belakangan dinonaktifkan admin
                    $dinonaktifkan = $status === 'diterima' && ! $akunAktif;
                @endphp

                <div class="flex items-center">
                    {{-- Step 1: Terkirim --}}
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-[#043277] text-white flex items-center justify-center font-bold">
                            ✓
                        </div>
                        <p class="text-xs font-semibold text-[#043277] mt-2 text-center">Terkirim</p>
                    </div>

                    <div class="flex-1 h-1 {{ $status !== 'pending' ? 'bg-[#043277]' : 'bg-gray-200' }}"></div>

                    {{-- Step 2: Diverifikasi --}}
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                            {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-600 border-2 border-yellow-400' : ($status === 'diterima' ? 'bg-[#043277] text-white' : 'bg-red-500 text-white') }}">
                            @if($status === 'pending')
                                ⏳
                            @elseif($status === 'diterima')
                                ✓
                            @else
                                ✕
                            @endif
                        </div>
                        <p class="text-xs font-semibold mt-2 text-center
                            {{ $status === 'pending' ? 'text-yellow-600' : ($status === 'diterima' ? 'text-[#043277]' : 'text-red-500') }}">
                            @if($status === 'pending')
                                Menunggu Verifikasi
                            @elseif($status === 'diterima')
                                Diterima
                            @else
                                Ditolak
                            @endif
                        </p>
                    </div>

                    <div class="flex-1 h-1 {{ $status === 'diterima' ? 'bg-[#043277]' : 'bg-gray-200' }}"></div>

                    {{-- Step 3: Aktif Magang (atau Dinonaktifkan) --}}
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                            {{ $dinonaktifkan ? 'bg-red-500 text-white' : ($status === 'diterima' ? 'bg-[#043277] text-white' : 'bg-gray-100 text-gray-400') }}">
                            {{ $dinonaktifkan ? '🔒' : '🎓' }}
                        </div>
                        <p class="text-xs font-semibold mt-2 text-center
                            {{ $dinonaktifkan ? 'text-red-500' : ($status === 'diterima' ? 'text-[#043277]' : 'text-gray-400') }}">
                            {{ $dinonaktifkan ? 'Dinonaktifkan' : 'Aktif Magang' }}
                        </p>
                    </div>
                </div>

                {{-- Pesan sesuai status --}}
                <div class="rounded-xl p-4
                    {{ $status === 'pending' ? 'bg-yellow-50 border border-yellow-200' : ($dinonaktifkan ? 'bg-red-50 border border-red-200' : ($status === 'diterima' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200')) }}">
                    @if($status === 'pending')
                        <p class="text-sm text-yellow-700">
                            <strong>Berkas Anda sedang diperiksa oleh admin BPS.</strong>
                            Anda akan bisa login ke dashboard setelah status berubah menjadi <strong>Diterima</strong>.
                        </p>
                    @elseif($dinonaktifkan)
                        <p class="text-sm text-red-700">
                            <strong>🔒 Akun Anda Dinonaktifkan</strong>
                        </p>
                        <p class="text-sm text-red-600 mt-1">
                            Pendaftaran Anda sebenarnya sudah <strong>diterima</strong>, tapi akun ini
                            saat ini dinonaktifkan oleh admin sehingga tidak bisa mengakses Dashboard.
                            Silakan hubungi admin BPS Kabupaten Ogan Ilir untuk informasi lebih lanjut.
                        </p>
                    @elseif($status === 'diterima')
                        <p class="text-sm text-green-700">
                            <strong>Selamat! Pendaftaran Anda telah diterima.</strong>
                            Anda sekarang bisa mengakses Dashboard Absensi.
                        </p>
                        <a href="{{ route('mahasiswa.dashboard') }}"
                           class="inline-block mt-3 bg-[#043277] text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-900 transition">
                            Buka Dashboard →
                        </a>
                   
@else

    <p class="text-sm text-red-700">
        <strong>Maaf, pendaftaran Anda belum dapat disetujui.</strong>
    </p>

    @if($mahasiswa->catatan_penolakan)

        <div class="mt-4 p-4 rounded-lg bg-white border border-red-200">
            <p class="font-semibold text-red-700 mb-2">
                Catatan dari Admin BPS
            </p>

            <p class="text-gray-700 whitespace-pre-line">
                {{ $mahasiswa->catatan_penolakan }}
            </p>
        </div>

    @endif

    <p class="text-sm text-red-600 mt-4">
        Silakan perbaiki berkas sesuai catatan di atas, kemudian lakukan
        pendaftaran kembali.
    </p>

@endif


                </div>

            </div>
        </div>

        {{-- ── Detail Data Pendaftar ───────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
            <div class="bg-[#043277] px-6 py-4">
                <h2 class="text-white font-bold text-base">
                    Detail Pendaftar
                    @if($mahasiswa->peran_kelompok === 'ketua')
                        <span class="text-blue-300 font-normal text-sm ml-1">(Ketua Kelompok)</span>
                    @endif
                </h2>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-400">Nama</dt>
                        <dd class="font-semibold text-gray-700">{{ $mahasiswa->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Email</dt>
                        <dd class="font-semibold text-gray-700">{{ $mahasiswa->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">{{ $mahasiswa->jenis_instansi === 'smk' ? 'NISN' : 'NIM' }}</dt>
                        <dd class="font-semibold text-gray-700">{{ $mahasiswa->nim ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Asal Instansi</dt>
                        <dd class="font-semibold text-gray-700">{{ $mahasiswa->universitas }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Tanggal Mulai</dt>
                        <dd class="font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($mahasiswa->tanggal_mulai)->translatedFormat('d F Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Tanggal Selesai</dt>
                        <dd class="font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($mahasiswa->tanggal_selesai)->translatedFormat('d F Y') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- ── Daftar Anggota Kelompok (jika ada) ──────── --}}
        @if($anggotaLain && $anggotaLain->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
            <div class="bg-[#043277] px-6 py-4">
                <h2 class="text-white font-bold text-base">Anggota Kelompok</h2>
            </div>
            <div class="p-6 space-y-3">
                @foreach($anggotaLain as $anggota)
                    <div class="flex items-center justify-between border border-gray-100 rounded-xl px-4 py-3">
                        <div>
                            <p class="font-semibold text-gray-700 text-sm">{{ $anggota->user->name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $anggota->jenis_instansi === 'smk' ? 'NISN' : 'NIM' }}: {{ $anggota->nim }}
                                — {{ $anggota->universitas }}
                            </p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full
                            {{ $anggota->status_pendaftaran === 'pending' ? 'bg-yellow-100 text-yellow-600' : ($anggota->status_pendaftaran === 'diterima' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') }}">
                            {{ ucfirst($anggota->status_pendaftaran) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Berkas yang Diunggah ─────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
            <div class="bg-[#043277] px-6 py-4">
                <h2 class="text-white font-bold text-base">Berkas Terunggah</h2>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('daftar.surat-pengantar', $mahasiswa) }}" target="_blank"
                   class="flex items-center gap-3 border border-gray-100 rounded-xl px-4 py-3 hover:bg-blue-50 transition">
                    <span class="text-2xl">📄</span>
                    <div>
                        <p class="font-semibold text-gray-700 text-sm">Surat Pengantar</p>
                        <p class="text-xs text-gray-400">Klik untuk melihat file</p>
                    </div>
                </a>
                <a href="{{ route('daftar.proposal', $mahasiswa) }}" target="_blank"
                   class="flex items-center gap-3 border border-gray-100 rounded-xl px-4 py-3 hover:bg-blue-50 transition">
                    <span class="text-2xl">📄</span>
                    <div>
                        <p class="font-semibold text-gray-700 text-sm">Proposal Magang</p>
                        <p class="text-xs text-gray-400">Klik untuk melihat file</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection