@extends('layouts.mahasiswa')
@section('title', 'Profil Saya')

@section('content')

<div class="space-y-5 max-w-2xl mx-auto">

    <div>
        <h1 class="text-xl font-bold text-gray-800">👤 Profil Saya</h1>
        <p class="text-gray-500 text-sm mt-0.5">Kelola data pribadi Anda.</p>
    </div>

    {{-- ═══ DATA YANG BISA DIEDIT ═══ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-white font-bold text-base">Edit Data Pribadi</h2>
        </div>
        <form method="POST" action="{{ route('mahasiswa.profil.update') }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                        @error('name') border-red-400 bg-red-50 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Email <span class="text-xs font-normal text-gray-400">(tidak bisa diubah)</span>
                </label>
                <input type="email" value="{{ auth()->user()->email }}" readonly
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-sm text-gray-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">No. WhatsApp</label>
                <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $mahasiswa->nomor_hp) }}"
                    placeholder="08xxxxxxxxxx"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                        @error('nomor_hp') border-red-400 bg-red-50 @enderror">
                @error('nomor_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin</label>
                <select name="jenis_kelamin"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="L" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <button type="submit"
                class="w-full py-3 bg-blue-600 text-white font-bold text-sm rounded-xl hover:bg-blue-700 active:scale-95 transition">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- ═══ GANTI PASSWORD ═══ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-700 px-6 py-4">
            <h2 class="text-white font-bold text-base">Ganti Password</h2>
        </div>
        <form method="POST" action="{{ route('mahasiswa.profil.password') }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Saat Ini</label>
                <input type="password" name="current_password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                        @error('current_password') border-red-400 bg-red-50 @enderror">
                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                <input type="password" name="password" placeholder="Minimal 8 karakter"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                        @error('password') border-red-400 bg-red-50 @enderror">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <button type="submit"
                class="w-full py-3 bg-gray-700 text-white font-bold text-sm rounded-xl hover:bg-gray-800 active:scale-95 transition">
                Ubah Password
            </button>
        </form>
    </div>

    {{-- ═══ DATA KRUSIAL — HANYA ADMIN YANG BOLEH UBAH ═══ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 flex items-center justify-between">
            <h2 class="text-gray-600 font-bold text-base">Data Magang</h2>
            <span class="text-xs text-gray-400">🔒 Hanya admin yang bisa mengubah</span>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-400">{{ $mahasiswa->jenis_instansi === 'smk' ? 'NISN' : 'NIM' }}</dt>
                    <dd class="font-semibold text-gray-500">{{ $mahasiswa->nim ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Asal Instansi</dt>
                    <dd class="font-semibold text-gray-500">{{ $mahasiswa->universitas }}</dd>
                </div>
                @if($mahasiswa->kode_kelompok)
                <div>
                    <dt class="text-gray-400">Kode Kelompok</dt>
                    <dd class="font-semibold text-gray-500">{{ $mahasiswa->kode_kelompok }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-gray-400">Tanggal Mulai Magang</dt>
                    <dd class="font-semibold text-gray-500">
                        {{ \Carbon\Carbon::parse($mahasiswa->tanggal_mulai)->translatedFormat('d F Y') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400">Tanggal Selesai Magang</dt>
                    <dd class="font-semibold text-gray-500">
                        {{ \Carbon\Carbon::parse($mahasiswa->tanggal_selesai)->translatedFormat('d F Y') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400">Status Magang</dt>
                    <dd class="font-semibold text-gray-500">{{ ucfirst($mahasiswa->status_magang) }}</dd>
                </div>
            </dl>
            <p class="text-xs text-gray-400 mt-4">
                Jika ada data di atas yang keliru, silakan hubungi admin BPS untuk melakukan koreksi.
            </p>
        </div>
    </div>

</div>

@endsection