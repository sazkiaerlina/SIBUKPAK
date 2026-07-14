@extends('layouts.mahasiswa')

@section('title', 'Laporan & Sertifikat')

@section('content')

<div class="space-y-5 max-w-2xl mx-auto">

    <div>
        <h1 class="text-xl font-bold text-gray-800">📁 Laporan & Sertifikat</h1>
        <p class="text-gray-500 text-sm mt-0.5">Unggah laporan akhir magang Anda, dan unduh sertifikat jika sudah tersedia.</p>
    </div>

    {{-- ═══ UPLOAD LAPORAN ═══ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#043277] px-6 py-4">
            <h2 class="text-white font-bold text-base">Laporan Akhir Magang</h2>
        </div>
        <div class="p-6 space-y-4">

            @if($mahasiswa->laporan_path)
                <div class="flex items-center gap-3 border border-green-200 bg-green-50 rounded-xl px-4 py-3">
                    <span class="text-2xl">✅</span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-green-700">Laporan sudah diunggah</p>
                        <p class="text-xs text-green-600">
                            Diunggah pada {{ \Carbon\Carbon::parse($mahasiswa->laporan_uploaded_at)->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>
                    <a href="{{ route('mahasiswa.laporan.show') }}" target="_blank"
                       class="text-xs font-semibold text-blue-600 hover:underline whitespace-nowrap">
                        Lihat File
                    </a>
                </div>
                <p class="text-xs text-gray-400">Ingin mengganti laporan? Unggah file baru di bawah untuk menimpa yang lama.</p>
            @endif

            <form method="POST" action="{{ route('mahasiswa.laporan.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf

                <label
                    x-data="{ fileName: '' }"
                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed
                           border-gray-300 rounded-2xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition"
                >
                    <template x-if="!fileName">
                        <div class="flex flex-col items-center pointer-events-none">
                            <svg class="w-8 h-8 text-blue-600 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm text-gray-600 font-medium">Klik untuk pilih file laporan</p>
                            <p class="text-xs text-gray-400 mt-1">Format PDF • Maksimal 5 MB</p>
                        </div>
                    </template>
                    <template x-if="fileName">
                        <div class="flex flex-col items-center pointer-events-none">
                            <span class="text-2xl mb-1">📄</span>
                            <p class="text-sm text-green-700 font-semibold" x-text="fileName"></p>
                        </div>
                    </template>
                    <input type="file" name="laporan" accept=".pdf" class="hidden"
                        @change="fileName = $event.target.files[0]?.name ?? ''">
                </label>
                @error('laporan') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                <button type="submit"
                    class="w-full py-3 bg-[#043277]  text-white font-bold text-sm rounded-xl hover:bg-blue-700 active:scale-95 transition">
                    {{ $mahasiswa->laporan_path ? 'Perbarui Laporan' : 'Unggah Laporan' }}
                </button>
            </form>
        </div>
    </div>

    {{-- ═══ SERTIFIKAT ═══ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#043277] px-6 py-4">
            <h2 class="text-white font-bold text-base">Sertifikat Magang</h2>
        </div>
        <div class="p-6">          

            @if($mahasiswa->sertifikat_path)

<div class="flex items-center gap-3 border border-blue-200 bg-blue-50 rounded-xl px-4 py-4">

    <span class="text-3xl">🎓</span>

    <div class="flex-1">

        <p class="text-sm font-semibold text-blue-700">
            Sertifikat Anda sudah tersedia!
        </p>

        <p class="text-xs text-blue-500">
            Sertifikat telah diunggah oleh admin dan siap diunduh.
        </p>

    </div>

    <a href="{{ route('mahasiswa.sertifikat.download') }}"
       class="bg-[#043277] text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-blue-900 transition whitespace-nowrap">

        ⬇️ Unduh

    </a>

</div>

@else

<div class="flex items-center gap-3 border border-gray-200 bg-gray-50 rounded-xl px-4 py-4">

    <span class="text-3xl">⏳</span>

    <div>

        <p class="text-sm font-semibold text-gray-600">
            Sertifikat belum tersedia
        </p>

        <p class="text-xs text-gray-400">
            Sertifikat akan muncul setelah admin mengunggah file sertifikat Anda.
        </p>

    </div>

</div>

@endif


        </div>
    </div>

</div>

@endsection