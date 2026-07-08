<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Presensi Magang') | Sistem Presensi</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#2563eb', dark: '#1d4ed8', light: '#dbeafe' },
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .btn-absen { transition: all 0.2s ease; -webkit-tap-highlight-color: transparent; }
        .btn-absen:active { transform: scale(0.97); }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#EAF2FF] min-h-screen">

    {{-- Topbar --}}
    <header class="bg-[#043277] text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">

           
               <div class="flex items-center gap-3">
    <img
        src="{{ asset('assets/images/logoSIBUKPAK.png') }}"
        alt="Logo SIBUKPAK"
        class="h-10 w-auto">
    <h1 class="text-white font-bold text-2xl">
        SIBUKPAK
    </h1>
</div>


            <div class="flex items-center gap-6">
                {{-- Navigasi 3 Menu (Desktop) --}}
                <nav class="hidden lg:flex gap-2">
                    <a href="{{ route('mahasiswa.dashboard') }}"
                       class="text-sm px-3 py-1.5 rounded-md transition {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-800' : 'hover:bg-blue-700' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('mahasiswa.riwayat') }}"
                       class="text-sm px-3 py-1.5 rounded-md transition {{ request()->routeIs('mahasiswa.riwayat') ? 'bg-blue-800' : 'hover:bg-blue-700' }}">
                        Riwayat Kehadiran
                    </a>
                    <a href="{{ route('mahasiswa.laporan') }}"
                       class="text-sm px-3 py-1.5 rounded-md transition {{ request()->routeIs('mahasiswa.laporan') ? 'bg-blue-800' : 'hover:bg-blue-700' }}">
                        Laporan & Sertifikat
                    </a>
                </nav>

                {{-- Dropdown Profil (kanan atas) --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="flex items-center gap-2 bg-blue-700/50 hover:bg-blue-700 px-2.5 py-1.5 rounded-full transition">
                        <div class="w-7 h-7 rounded-full bg-white text-blue-700 flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium hidden sm:inline">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-cloak x-transition
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 text-gray-700 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('mahasiswa.profil') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 transition">
                            <span>👤</span> Edit Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                <span>🚪</span> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div id="flash-success" class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
                <span>✅</span> {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
                <span>⚠️</span> {{ session('warning') }}
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-xl text-sm shadow-sm">
                <p class="font-semibold mb-1">❌ Terjadi kesalahan:</p>
                <ul class="list-disc ml-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Content Area --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 pb-24 lg:pb-8">
        @yield('content')
    </main>

    {{-- Bottom Nav (Mobile) — 3 menu --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around py-2 z-50 lg:hidden shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="flex flex-col items-center gap-0.5 px-4 py-1 transition {{ request()->routeIs('mahasiswa.dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            <span class="text-[10px] font-medium mt-1">Dashboard</span>
        </a>
        <a href="{{ route('mahasiswa.riwayat') }}"
           class="flex flex-col items-center gap-0.5 px-4 py-1 transition {{ request()->routeIs('mahasiswa.riwayat') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
            </svg>
            <span class="text-[10px] font-medium mt-1">Riwayat</span>
        </a>
        <a href="{{ route('mahasiswa.laporan') }}"
           class="flex flex-col items-center gap-0.5 px-4 py-1 transition {{ request()->routeIs('mahasiswa.laporan') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-500' }}">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-[10px] font-medium mt-1">Laporan</span>
        </a>
    </nav>

    {{-- Alpine.js (untuk dropdown profil) --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
    <script>
        setTimeout(() => {
            const el = document.getElementById('flash-success');
            if (el) el.style.display = 'none';
        }, 4000);
    </script>
</body>
</html>