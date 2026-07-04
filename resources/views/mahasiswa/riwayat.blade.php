@extends('layouts.mahasiswa')

@section('title', 'Riwayat Kehadiran')

@section('content')

<div class="space-y-5">

    <div>
        <h1 class="text-xl font-bold text-gray-800">📋 Riwayat Kehadiran</h1>
        <p class="text-gray-500 text-sm mt-0.5">Rekap kehadiran Anda selama masa magang.</p>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach([
            ['label' => 'Hadir', 'value' => $statistik['hadir'], 'color' => 'green', 'emoji' => '✅'],
            ['label' => 'Sakit', 'value' => $statistik['sakit'], 'color' => 'yellow', 'emoji' => '🤒'],
            ['label' => 'Izin',  'value' => $statistik['izin'],  'color' => 'blue',   'emoji' => '📋'],
            ['label' => 'Alpa',  'value' => $statistik['alpa'],  'color' => 'red',    'emoji' => '❌'],
        ] as $stat)
        <div class="bg-{{ $stat['color'] }}-50 rounded-xl p-4 text-center border border-{{ $stat['color'] }}-100">
            <div class="text-2xl">{{ $stat['emoji'] }}</div>
            <div class="text-2xl font-bold text-{{ $stat['color'] }}-700 mt-1">{{ $stat['value'] }}</div>
            <div class="text-xs text-{{ $stat['color'] }}-600 font-medium">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Filter Bulan (opsional, sederhana) --}}
    <form method="GET" class="flex items-center gap-2">
        <select name="bulan" onchange="this.form.submit()"
            class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach($daftarBulan as $key => $label)
                <option value="{{ $key }}" {{ request('bulan', now()->format('Y-m')) === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Tabel Riwayat (Desktop) --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold">Tanggal</th>
                    <th class="text-left px-5 py-3 font-semibold">Jam Masuk</th>
                    <th class="text-left px-5 py-3 font-semibold">Jam Pulang</th>
                    <th class="text-left px-5 py-3 font-semibold">Status</th>
                    <th class="text-left px-5 py-3 font-semibold">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($presensi as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-medium text-gray-700">
                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-5 py-3 text-gray-600 font-mono">
                        {{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}
                    </td>
                    <td class="px-5 py-3 text-gray-600 font-mono">
                        {{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}
                    </td>
                    <td class="px-5 py-3">
                        @php
                            $warna = match($item->status) {
                                'hadir'  => 'bg-green-100 text-green-700',
                                'sakit'  => 'bg-yellow-100 text-yellow-700',
                                'izin'   => 'bg-blue-100 text-blue-700',
                                default  => 'bg-red-100 text-red-700',
                            };
                        @endphp
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $warna }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                        Belum ada data kehadiran di bulan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- List Card (Mobile) --}}
    <div class="md:hidden space-y-3">
        @forelse($presensi as $item)
        @php
            $warna = match($item->status) {
                'hadir'  => 'bg-green-100 text-green-700',
                'sakit'  => 'bg-yellow-100 text-yellow-700',
                'izin'   => 'bg-blue-100 text-blue-700',
                default  => 'bg-red-100 text-red-700',
            };
        @endphp
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <p class="font-semibold text-gray-700 text-sm">
                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                </p>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $warna }}">
                    {{ ucfirst($item->status) }}
                </span>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
                <span>Masuk: <strong class="text-gray-700 font-mono">{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</strong></span>
                <span>Pulang: <strong class="text-gray-700 font-mono">{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</strong></span>
            </div>
            @if($item->keterangan)
                <p class="text-xs text-gray-400 mt-2">📝 {{ $item->keterangan }}</p>
            @endif
        </div>
        @empty
        <div class="bg-white border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center">
            <p class="text-3xl mb-2">🗓️</p>
            <p class="text-sm text-gray-400">Belum ada data kehadiran di bulan ini.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div>
        {{ $presensi->links() }}
    </div>

</div>

@endsection