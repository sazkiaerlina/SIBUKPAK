@extends('layouts.mahasiswa')

@section('title', 'Dashboard')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Kolom Kiri & Tengah (Utama) --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Kartu Profil & Jam --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 sm:gap-0">
                    <div>
                        <p class="text-blue-200 text-xs font-medium uppercase tracking-wide">Selamat datang,</p>
                        <h2 class="text-xl md:text-2xl font-bold mt-0.5">{{ auth()->user()->name }}</h2>
                        <p class="text-blue-200 text-sm mt-1">NIM: <span class="text-white font-semibold">{{ $mahasiswa->nim ?? '-' }}</span></p>
                        <p class="text-blue-200 text-xs mt-1">{{ $mahasiswa->jurusan ?? '' }}</p>
                    </div>
                    <div class="text-left sm:text-right w-full sm:w-auto border-t border-blue-500/40 sm:border-0 pt-3 sm:pt-0">
                        <p id="jam-digital" class="text-2xl md:text-3xl font-bold font-mono tracking-wider">00:00:00</p>
                        <p class="text-blue-200 text-xs mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                {{-- Progress Magang --}}
                @if($mahasiswa)
                    @php
                        $mulai    = $mahasiswa->tanggal_mulai;
                        $selesai  = $mahasiswa->tanggal_selesai;
                        $today    = \Carbon\Carbon::today();
                        $total    = $mulai->diffInDays($selesai) ?: 1;
                        $passed   = $today->lt($mulai) ? 0 : min($mulai->diffInDays($today), $total);
                        $percent  = round(($passed / $total) * 100);
                    @endphp
                    <div class="mt-6">
                        <div class="flex justify-between text-xs text-blue-200 mb-1">
                            <span>Progress Magang</span>
                            <span>{{ $percent }}%</span>
                        </div>
                        <div class="bg-blue-800 rounded-full h-2">
                            <div class="bg-white rounded-full h-2 transition-all" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-blue-200 mt-1">
                            <span>{{ $mulai->format('d M Y') }}</span>
                            <span>{{ $selesai->format('d M Y') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ═══════════════════════════════════════════════════
                 BANNER STATUS MASA MAGANG
                 Muncul HANYA jika belum mulai atau sudah selesai.
            ═══════════════════════════════════════════════════ --}}
            @unless($masaAktif)
                @if(!$sudahMulai)
                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-start gap-3">
                        <span class="text-3xl">🗓️</span>
                        <div>
                            <p class="font-semibold text-blue-800 text-sm">Masa magang Anda belum dimulai</p>
                            <p class="text-blue-600 text-xs mt-1">
                                Fitur absen dan pengajuan izin akan aktif otomatis mulai
                                <strong>{{ $mahasiswa->tanggal_mulai->translatedFormat('d F Y') }}</strong>.
                                Silakan kembali lagi pada tanggal tersebut.
                            </p>
                        </div>
                    </div>
                @elseif($sudahSelesai)
                    <div class="bg-gray-100 border border-gray-200 rounded-2xl p-5 flex items-start gap-3">
                        <span class="text-3xl">🎉</span>
                        <div>
                            <p class="font-semibold text-gray-700 text-sm">Masa magang Anda telah selesai</p>
                            <p class="text-gray-500 text-xs mt-1">
                                Periode magang berakhir pada
                                <strong>{{ $mahasiswa->tanggal_selesai->translatedFormat('d F Y') }}</strong>.
                                Fitur absen dan izin sudah tidak dapat digunakan lagi.
                                Jangan lupa unggah laporan akhir Anda di menu
                                <strong>Laporan & Sertifikat</strong>. Terima kasih atas kontribusinya! 🙏
                            </p>
                        </div>
                    </div>
                @endif
            @endunless

            {{-- Status Absen Hari Ini --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-700 text-sm mb-3">📋 Status Absen Hari Ini</h3>

                @if(!$masaAktif)
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-2xl">🚫</span>
                        <div>
                            <p class="font-semibold text-gray-500 text-sm">Absen tidak tersedia</p>
                            <p class="text-gray-400 text-xs">
                                {{ !$sudahMulai ? 'Masa magang belum dimulai.' : 'Masa magang sudah selesai.' }}
                            </p>
                        </div>
                    </div>
                @elseif($presensiHariIni)
                    @php $p = $presensiHariIni; @endphp

                    @if(in_array($p->status, ['sakit', 'izin']))
                        <div class="flex items-center gap-3 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                            <span class="text-2xl">{{ $p->status === 'sakit' ? '🤒' : '📝' }}</span>
                            <div>
                                <p class="font-semibold text-yellow-800 text-sm">{{ ucfirst($p->status) }} — sudah dilaporkan</p>
                                <p class="text-yellow-600 text-xs">{{ $p->keterangan }}</p>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                                <p class="text-xs text-gray-500 font-medium">Jam Masuk</p>
                                <p class="text-2xl font-bold text-green-700 font-mono mt-0.5">
                                    {{ $p->jam_masuk ? \Carbon\Carbon::parse($p->jam_masuk)->format('H:i') : '--:--' }}
                                </p>
                                @if($p->jam_masuk)
                                    <p class="text-xs text-green-600 mt-0.5">✅ Tercatat</p>
                                @else
                                    <p class="text-xs text-gray-400 mt-0.5">Belum masuk</p>
                                @endif
                            </div>
                            <div class="bg-orange-50 rounded-xl p-4 border border-orange-200">
                                <p class="text-xs text-gray-500 font-medium">Jam Pulang</p>
                                <p class="text-2xl font-bold text-orange-700 font-mono mt-0.5">
                                    {{ $p->jam_pulang ? \Carbon\Carbon::parse($p->jam_pulang)->format('H:i') : '--:--' }}
                                </p>
                                @if($p->jam_pulang)
                                    <p class="text-xs text-orange-600 mt-0.5">✅ Tercatat</p>
                                @else
                                    <p class="text-xs text-gray-400 mt-0.5">Belum pulang</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-2xl">⏳</span>
                        <div>
                            <p class="font-semibold text-gray-600 text-sm">Belum absen hari ini</p>
                            <p class="text-gray-400 text-xs">Tekan tombol absen di bawah</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ═══════════════════════════════════════════════════
                 TOMBOL ABSEN
            ═══════════════════════════════════════════════════ --}}
            @php
                $sudahMasuk        = $presensiHariIni && $presensiHariIni->sudahMasuk();
                $sudahPulang       = $presensiHariIni && $presensiHariIni->sudahPulang();
                $adaKetidakhadiran = $presensiHariIni && in_array($presensiHariIni->status, ['sakit', 'izin']);
            @endphp

            @if(!$masaAktif)
                {{-- Tombol non-aktif, tanpa fungsi apapun, murni informasi --}}
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="w-full py-5 rounded-2xl text-center font-bold text-sm
                                bg-gray-100 text-gray-400 border-2 border-dashed border-gray-200">
                        <div class="text-3xl mb-1">🔒</div>
                        <div>Absen Masuk</div>
                        <div class="text-[10px] font-normal mt-1 px-2">
                            {{ !$sudahMulai ? 'Aktif saat magang dimulai' : 'Magang telah selesai' }}
                        </div>
                    </div>
                    <div class="w-full py-5 rounded-2xl text-center font-bold text-sm
                                bg-gray-100 text-gray-400 border-2 border-dashed border-gray-200">
                        <div class="text-3xl mb-1">🔒</div>
                        <div>Absen Pulang</div>
                        <div class="text-[10px] font-normal mt-1 px-2">
                            {{ !$sudahMulai ? 'Aktif saat magang dimulai' : 'Magang telah selesai' }}
                        </div>
                    </div>
                </div>
            @elseif(!$adaKetidakhadiran)
            <div class="mb-5">

                {{-- ── MODAL MAP ABSEN ──────────────────────────────────── --}}
                 <div id="modal-absen"
                    class="fixed inset-0 bg-black/70 z-[9999] hidden flex-col"
                    style="height: 100dvh;">

                    <div class="bg-white px-4 py-3 flex items-center justify-between shadow">
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm" id="modal-judul">Absen Masuk</h3>
                            <p class="text-xs text-gray-500" id="modal-subjudul">Pastikan Anda berada di area kantor</p>
                        </div>
                        <button onclick="tutupModalAbsen()"
                                class="text-gray-400 hover:text-gray-700 text-2xl leading-none px-1">✕</button>
                    </div>

                    <div id="map" class="flex-1 w-full" style="min-height: 340px;"></div>

                    
                        <div class="bg-white px-4 py-4 shadow-lg space-y-3" style="padding-bottom: calc(1rem + env(safe-area-inset-bottom));">
                    <div id="info-jarak"
                        class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200">

                            <div class="text-2xl" id="jarak-icon">📡</div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500" id="jarak-label">Mendeteksi lokasi Anda...</p>
                                <p class="font-bold text-gray-800 text-sm" id="jarak-value">—</p>
                            </div>
                        </div>

                        <button id="btn-konfirmasi"
                                onclick="konfirmasiAbsen()"
                                disabled
                                class="w-full py-4 rounded-2xl text-white font-bold text-sm
                                    bg-gray-300 cursor-not-allowed transition-all duration-300">
                            Mendeteksi lokasi...
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button
                        onclick="bukaModalAbsen('masuk')"
                        @if($sudahMasuk) disabled @endif
                        class="btn-absen w-full py-5 rounded-2xl text-center font-bold text-sm shadow-md
                            {{ $sudahMasuk
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-2 border-dashed border-gray-200'
                                : 'bg-green-500 hover:bg-green-600 text-white shadow-green-200' }}">
                        <div class="text-3xl mb-1">{{ $sudahMasuk ? '✅' : '🟢' }}</div>
                        <div>{{ $sudahMasuk ? 'Sudah Masuk' : 'Absen Masuk' }}</div>
                    </button>

                    <button
                        onclick="bukaModalAbsen('pulang')"
                        @if(!$sudahMasuk || $sudahPulang) disabled @endif
                        class="btn-absen w-full py-5 rounded-2xl text-center font-bold text-sm shadow-md
                            {{ (!$sudahMasuk || $sudahPulang)
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-2 border-dashed border-gray-200'
                                : 'bg-orange-500 hover:bg-orange-600 text-white shadow-orange-200' }}">
                        <div class="text-3xl mb-1">{{ $sudahPulang ? '✅' : '🔴' }}</div>
                        <div>{{ $sudahPulang ? 'Sudah Pulang' : 'Absen Pulang' }}</div>
                    </button>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════
                 SHORTCUT IZIN/SAKIT
                 Disembunyikan total kalau masa magang tidak aktif.
            ═══════════════════════════════════════════════════ --}}
            @if($masaAktif && !$adaKetidakhadiran)
            <a href="{{ route('mahasiswa.ketidakhadiran.form') }}"
               class="block w-full py-3.5 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl text-center text-blue-700 text-sm font-medium transition">
                📋 Tidak hadir hari ini? Ajukan Izin / Sakit
            </a>
            @endif
        </div>

        {{-- Kolom Kanan (Statistik) --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">📊 Statistik Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-2 gap-3">
                @foreach([
                    ['label' => 'Hadir',  'value' => $statistik['hadir'], 'color' => 'green', 'emoji' => '✅'],
                    ['label' => 'Sakit',  'value' => $statistik['sakit'], 'color' => 'yellow','emoji' => '🤒'],
                    ['label' => 'Izin',   'value' => $statistik['izin'],  'color' => 'blue',  'emoji' => '📋'],
                    ['label' => 'Terlambat',   'value' => $statistik['terlambat'],  'color' => 'orange',   'emoji' => '⏰'],
                ] as $stat)
                <div class="bg-{{ $stat['color'] }}-50 rounded-xl p-3 text-center border border-{{ $stat['color'] }}-100">
                    <div class="text-xl">{{ $stat['emoji'] }}</div>
                    <div class="text-2xl font-bold text-{{ $stat['color'] }}-700 mt-1">{{ $stat['value'] }}</div>
                    <div class="text-xs text-{{ $stat['color'] }}-600 font-medium">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
function updateJam() {
    const now = new Date();
    const hh  = String(now.getHours()).padStart(2, '0');
    const mm  = String(now.getMinutes()).padStart(2, '0');
    const ss  = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('jam-digital').textContent = `${hh}:${mm}:${ss}`;
}
updateJam();
setInterval(updateJam, 1000);

const KANTOR = {
    lat    : {{ config('kantor.latitude') }},
    lng    : {{ config('kantor.longitude') }},
    radius : {{ config('kantor.radius_meter') }},
    nama   : "{{ config('kantor.nama') }}",
    zoom   : {{ config('kantor.zoom') }},
};

const CSRF = document.querySelector('meta[name="csrf-token"]').content;

let map           = null;
let markerUser    = null;
let lingkaran     = null;
let tipeAbsen     = null;
let koordinatUser = null;
let jarakUser     = null;
let watchId       = null;


function bukaModalAbsen(tipe) {
    tipeAbsen = tipe;
    document.body.style.overflow = 'hidden';

    document.getElementById('modal-judul').textContent =
        tipe === 'masuk' ? 'Absen Masuk' : 'Absen Pulang';

    document.getElementById('modal-subjudul').textContent =
        'Pastikan Anda berada di area kantor';

    resetTombolKonfirmasi();

    const modal = document.getElementById('modal-absen');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        inisialisasiMap();
        mulaiDeteksiLokasi();
    }, 100);
}

function tutupModalAbsen() {
    document.body.style.overflow = '';
    document.getElementById('modal-absen').classList.add('hidden');
    document.getElementById('modal-absen').classList.remove('flex');


    if (watchId !== null) {
        navigator.geolocation.clearWatch(watchId);
        watchId = null;
    }

    if (map !== null) {
        map.remove();
        map        = null;
        markerUser = null;
        lingkaran  = null;
    }

    koordinatUser = null;
    jarakUser     = null;
}

function inisialisasiMap() {
    if (map !== null) return;

    map = L.map('map').setView([KANTOR.lat, KANTOR.lng], KANTOR.zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution : '© OpenStreetMap contributors',
        maxZoom     : 19,
    }).addTo(map);

    const ikonKantor = L.divIcon({
        className : '',
        html      : `<div style="background:#2563eb;color:white;border-radius:50%;
                        width:36px;height:36px;display:flex;align-items:center;
                        justify-content:center;font-size:18px;border:3px solid white;
                        box-shadow:0 2px 8px rgba(0,0,0,0.3);">🏢</div>`,
        iconSize  : [36, 36],
        iconAnchor: [18, 18],
    });

    L.marker([KANTOR.lat, KANTOR.lng], { icon: ikonKantor })
     .addTo(map)
     .bindPopup(`<b>${KANTOR.nama}</b><br>Radius: ${KANTOR.radius} meter`)
     .openPopup();

    lingkaran = L.circle([KANTOR.lat, KANTOR.lng], {
        radius      : KANTOR.radius,
        color       : '#2563eb',
        fillColor   : '#2563eb',
        fillOpacity : 0.10,
        weight      : 2,
        dashArray   : '6, 4',
    }).addTo(map);
}

function mulaiDeteksiLokasi() {
    if (!('geolocation' in navigator)) {
        tampilkanError('Browser Anda tidak mendukung GPS.');
        return;
    }

    document.getElementById('jarak-label').textContent = 'Mendeteksi lokasi GPS...';
    document.getElementById('jarak-value').textContent = '—';
    document.getElementById('jarak-icon').textContent  = '📡';

    watchId = navigator.geolocation.watchPosition(
        onLokasiBerhasil,
        onLokasiGagal,
        {
            enableHighAccuracy : true,
            timeout            : 15000,
            maximumAge         : 3000,
        }
    );
}

function onLokasiBerhasil(position) {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    koordinatUser = { lat, lng };
    jarakUser     = hitungJarak(lat, lng, KANTOR.lat, KANTOR.lng);

    const ikonUser = L.divIcon({
        className : '',
        html      : `<div style="background:#f97316;color:white;border-radius:50%;
                        width:32px;height:32px;display:flex;align-items:center;
                        justify-content:center;font-size:16px;border:3px solid white;
                        box-shadow:0 2px 8px rgba(0,0,0,0.3);">📍</div>`,
        iconSize  : [32, 32],
        iconAnchor: [16, 16],
    });

    if (markerUser) {
        markerUser.setLatLng([lat, lng]);
    } else {
        markerUser = L.marker([lat, lng], { icon: ikonUser })
            .addTo(map)
            .bindPopup('Lokasi Anda');
    }

    map.fitBounds(
        L.latLngBounds([KANTOR.lat, KANTOR.lng], [lat, lng]),
        { padding: [40, 40] }
    );

    updateInfoJarak(jarakUser);
}

function onLokasiGagal(error) {
    const pesan = {
        1 : 'Izin lokasi ditolak. Aktifkan izin GPS di browser.',
        2 : 'Lokasi tidak tersedia. Coba di tempat lebih terbuka.',
        3 : 'Waktu deteksi habis. Pastikan GPS aktif.',
    };
    tampilkanError(pesan[error.code] ?? 'Gagal mendeteksi lokasi.');
}

function updateInfoJarak(jarak) {
    const elLabel = document.getElementById('jarak-label');
    const elValue = document.getElementById('jarak-value');
    const elIcon  = document.getElementById('jarak-icon');
    const elBtn   = document.getElementById('btn-konfirmasi');
    const elInfo  = document.getElementById('info-jarak');

    const dalamArea = jarak <= KANTOR.radius;

    elValue.textContent = `${Math.round(jarak)} meter dari kantor`;

    if (dalamArea) {
        elIcon.textContent  = '✅';
        elLabel.textContent = 'Anda berada di area kantor';
        elInfo.className    = 'flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-300';

        const labelBtn = tipeAbsen === 'masuk'
            ? 'Konfirmasi Absen Masuk'
            : 'Konfirmasi Absen Pulang';
        const warnaBg = tipeAbsen === 'masuk'
            ? 'bg-green-500 hover:bg-green-600'
            : 'bg-orange-500 hover:bg-orange-600';

        elBtn.disabled    = false;
        elBtn.className   = `w-full py-4 rounded-2xl text-white font-bold text-sm
                             ${warnaBg} active:scale-95 transition-all duration-200`;
        elBtn.textContent = labelBtn;

    } else {
        const selisih = Math.round(jarak - KANTOR.radius);

        elIcon.textContent  = '❌';
        elLabel.textContent = `Terlalu jauh — radius maksimal ${KANTOR.radius}m`;
        elInfo.className    = 'flex items-center gap-3 p-3 rounded-xl bg-red-50 border border-red-300';

        elBtn.disabled    = true;
        elBtn.className   = 'w-full py-4 rounded-2xl text-gray-400 font-bold text-sm bg-gray-200 cursor-not-allowed';
        elBtn.textContent = `❌ Di luar area (${selisih}m terlalu jauh)`;
    }
}

async function konfirmasiAbsen() {
    if (!koordinatUser) {
        alert('Lokasi belum terdeteksi. Tunggu beberapa saat.');
        return;
    }

    const btn = document.getElementById('btn-konfirmasi');
    btn.disabled    = true;
    btn.textContent = '⏳ Menyimpan absen...';

    const url = tipeAbsen === 'masuk'
        ? '{{ route("mahasiswa.absen.masuk") }}'
        : '{{ route("mahasiswa.absen.pulang") }}';

    try {
        const response = await fetch(url, {
            method  : 'POST',
            headers : {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : CSRF,
                'Accept'       : 'application/json',
            },
            body: JSON.stringify({
                latitude  : koordinatUser.lat,
                longitude : koordinatUser.lng,
            }),
        });

        const data = await response.json();

        tutupModalAbsen();

        if (data.status === 'success') {
            tampilkanFlash('success', data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            tampilkanFlash('warning', data.message);
        }

    } catch (err) {
        tampilkanFlash('error', 'Terjadi kesalahan koneksi. Coba lagi.');
        btn.disabled    = false;
        btn.textContent = 'Coba Lagi';
    }
}

function resetTombolKonfirmasi() {
    const btn = document.getElementById('btn-konfirmasi');
    btn.disabled    = true;
    btn.className   = 'w-full py-4 rounded-2xl text-white font-bold text-sm bg-gray-300 cursor-not-allowed';
    btn.textContent = 'Mendeteksi lokasi...';

    document.getElementById('jarak-icon').textContent  = '📡';
    document.getElementById('jarak-label').textContent = 'Mendeteksi lokasi GPS...';
    document.getElementById('jarak-value').textContent = '—';
    document.getElementById('info-jarak').className    =
        'flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200';
}

function tampilkanError(pesan) {
    document.getElementById('jarak-icon').textContent  = '⚠️';
    document.getElementById('jarak-label').textContent = pesan;
    document.getElementById('jarak-value').textContent = '—';
}

function tampilkanFlash(tipe, pesan) {
    const warna = {
        success : 'bg-green-100 border-green-400 text-green-800',
        warning : 'bg-yellow-100 border-yellow-400 text-yellow-800',
        error   : 'bg-red-100 border-red-400 text-red-800',
    };
    const emoji = { success: '✅', warning: '⚠️', error: '❌' };

    const el = document.createElement('div');
    el.className = `${warna[tipe]} border px-4 py-3 rounded-xl text-sm font-medium
                    flex items-center gap-2 fixed top-20 left-4 right-4 z-50 shadow-lg`;
    el.innerHTML = `<span>${emoji[tipe]}</span> ${pesan}`;
    document.body.appendChild(el);

    setTimeout(() => el.remove(), 4000);
}

function hitungJarak(lat1, lon1, lat2, lon2) {
    const R    = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a    = Math.sin(dLat / 2) * Math.sin(dLat / 2)
               + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
               * Math.sin(dLon / 2) * Math.sin(dLon / 2);
    return 6371000 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}
</script>
@endpush