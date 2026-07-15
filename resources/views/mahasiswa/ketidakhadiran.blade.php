@extends('layouts.mahasiswa')

@section('title', 'Izin / Sakit')

@section('content')

<div class="max-w-xl mx-auto space-y-5">

    <div>
        <h1 class="text-xl font-bold text-gray-800">📋 Ajukan Izin / Sakit</h1>
        <p class="text-gray-500 text-sm mt-0.5">Laporkan ketidakhadiran Anda hari ini beserta keterangannya.</p>
    </div>

    @if($sudahAbsenMasuk)
        {{-- Info blokir: sudah absen masuk hari ini, tidak bisa ajukan izin/sakit --}}
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-start gap-3">
            <span class="text-3xl">🚫</span>
            <div>
                <p class="font-semibold text-red-700 text-sm">Tidak bisa mengajukan izin/sakit hari ini</p>
                <p class="text-red-500 text-xs mt-1">
                    Anda sudah melakukan absen masuk hari ini, jadi status hari ini sudah tercatat
                    sebagai <strong>Hadir</strong> dan tidak bisa ditimpa jadi izin/sakit.
                </p>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-white font-bold text-base">Form Pengajuan</h2>
            </div>

            <form method="POST" action="{{ route('mahasiswa.ketidakhadiran.store') }}"
                  enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 border border-gray-300 rounded-xl px-4 py-3 cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="status" value="sakit"
                                {{ old('status') === 'sakit' ? 'checked' : '' }}
                                class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium">🤒 Sakit</span>
                        </label>
                        <label class="flex items-center gap-2 border border-gray-300 rounded-xl px-4 py-3 cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="status" value="izin"
                                {{ old('status') === 'izin' ? 'checked' : '' }}
                                class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium">📋 Izin</span>
                        </label>
                    </div>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Keterangan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keterangan" rows="4" minlength="10" maxlength="500"
                        placeholder="Jelaskan alasan ketidakhadiran Anda (minimal 10 karakter)..."
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm
                            focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                            @error('keterangan') border-red-400 bg-red-50 @enderror"
                    >{{ old('keterangan') }}</textarea>
                    @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Bukti Dokumen --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Bukti Dokumen <span class="text-gray-400 text-xs font-normal">(opsional, contoh: surat dokter)</span>
                    </label>
                    <input type="file" name="bukti_dokumen" accept=".jpg,.jpeg,.png"
                        class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2.5
                            focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Format JPG/PNG, maksimal 2 MB.</p>
                    @error('bukti_dokumen') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                    class="w-full py-3 bg-blue-600 text-white font-bold text-sm rounded-xl hover:bg-blue-700 active:scale-95 transition">
                    Kirim Pengajuan
                </button>
            </form>
        </div>
    @endif

    <a href="{{ route('mahasiswa.dashboard') }}"
       class="block text-center text-sm text-gray-500 hover:text-gray-700 transition">
        ← Kembali ke Dashboard
    </a>

</div>

@endsection