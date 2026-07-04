<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    /** Konstanta koordinat kantor diambil dari config */
    private function getKantorConfig(): array
    {
        return [
            'latitude'     => config('kantor.latitude'),
            'longitude'    => config('kantor.longitude'),
            'radius_meter' => config('kantor.radius_meter'),
        ];
    }

    /**
     * Hitung jarak 2 koordinat menggunakan formula Haversine.
     * Return: jarak dalam meter.
     */
    private function hitungJarak(
        float $lat1, float $lon1,
        float $lat2, float $lon2
    ): float {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /** Absen Masuk */
    public function absenMasuk(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Profil mahasiswa tidak ditemukan.',
            ], 422);
        }

        $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $existing = $mahasiswa->presensiHariIni();

        // ── Blokir: sudah lapor izin/sakit hari ini, tidak boleh absen masuk ──
        if ($existing && in_array($existing->status, ['izin', 'sakit'])) {
            return response()->json([
                'status'  => 'warning',
                'message' => 'Anda sudah mengajukan ' . $existing->status . ' untuk hari ini, tidak bisa melakukan absen masuk.',
            ]);
        }

        // ── Blokir: sudah absen masuk hari ini ──
        if ($existing && $existing->sudahMasuk()) {
            return response()->json([
                'status'  => 'warning',
                'message' => 'Anda sudah melakukan absen masuk hari ini.',
            ]);
        }

        // ── Validasi Geolocation ──────────────────────────────
        $kantor = $this->getKantorConfig();
        $jarak  = $this->hitungJarak(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $kantor['latitude'],
            (float) $kantor['longitude']
        );

        if ($jarak > $kantor['radius_meter']) {
            return response()->json([
                'status'  => 'error',
                'message' => "Anda berada {$jarak} meter dari kantor. Maksimal radius: {$kantor['radius_meter']} meter.",
                'jarak'   => $jarak,
            ], 422);
        }

        // ── Simpan Presensi ───────────────────────────────────
        Presensi::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal'      => Carbon::today(),
            ],
            [
                'jam_masuk'   => Carbon::now()->format('H:i:s'),
                'status'      => 'hadir',
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'jarak_meter' => $jarak,
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Absen masuk berhasil! Jam: ' . Carbon::now()->format('H:i'),
            'jarak'   => $jarak,
        ]);
    }

    /** Absen Pulang */
    public function absenPulang(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Profil mahasiswa tidak ditemukan.',
            ], 422);
        }

        $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $presensiHariIni = $mahasiswa->presensiHariIni();

        if (!$presensiHariIni || !$presensiHariIni->sudahMasuk()) {
            return response()->json([
                'status'  => 'warning',
                'message' => 'Anda belum melakukan absen masuk hari ini.',
            ]);
        }

        if ($presensiHariIni->sudahPulang()) {
            return response()->json([
                'status'  => 'warning',
                'message' => 'Anda sudah melakukan absen pulang hari ini.',
            ]);
        }

        // ── Validasi Geolocation ──────────────────────────────
        $kantor = $this->getKantorConfig();
        $jarak  = $this->hitungJarak(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $kantor['latitude'],
            (float) $kantor['longitude']
        );

        if ($jarak > $kantor['radius_meter']) {
            return response()->json([
                'status'  => 'error',
                'message' => "Anda berada {$jarak} meter dari kantor. Maksimal radius: {$kantor['radius_meter']} meter.",
                'jarak'   => $jarak,
            ], 422);
        }

        // ── Simpan Jam Pulang ─────────────────────────────────
        $presensiHariIni->update([
            'jam_pulang' => Carbon::now()->format('H:i:s'),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Absen pulang berhasil! Jam: ' . Carbon::now()->format('H:i'),
            'jarak'   => $jarak,
        ]);
    }

    /** Form Ketidakhadiran */
    public function formKetidakhadiran()
    {
        $mahasiswa       = Auth::user()->mahasiswa;
        $presensiHariIni = $mahasiswa?->presensiHariIni();

        // Halaman tetap bisa dibuka kapan saja; info status ditampilkan di view.
        $sudahAbsenMasuk = $presensiHariIni && $presensiHariIni->sudahMasuk();

        return view('mahasiswa.ketidakhadiran', compact('presensiHariIni', 'sudahAbsenMasuk'));
    }

    /** Submit Ketidakhadiran */
    public function submitKetidakhadiran(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $validated = $request->validate([
            'status'        => ['required', 'in:sakit,izin'],
            'keterangan'    => ['required', 'string', 'min:10', 'max:500'],
            'bukti_dokumen' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'status.required'      => 'Pilih status ketidakhadiran.',
            'keterangan.required'  => 'Keterangan wajib diisi.',
            'keterangan.min'       => 'Keterangan minimal 10 karakter.',
            'bukti_dokumen.image'  => 'File harus berupa gambar.',
            'bukti_dokumen.max'    => 'Ukuran file maksimal 2MB.',
        ]);

        // ── Blokir: sudah absen masuk hari ini, tidak boleh menimpa jadi izin/sakit ──
        $presensiHariIni = $mahasiswa->presensiHariIni();

        if ($presensiHariIni && $presensiHariIni->sudahMasuk()) {
            return back()->withErrors([
                'status' => 'Anda sudah melakukan absen masuk hari ini, sehingga tidak bisa mengajukan izin/sakit untuk hari yang sama.',
            ])->withInput();
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_dokumen')) {
            $buktiPath = $request->file('bukti_dokumen')
                ->store('bukti-presensi', 'public');
        }

        Presensi::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal'      => Carbon::today(),
            ],
            [
                'status'        => $validated['status'],
                'keterangan'    => $validated['keterangan'],
                'bukti_dokumen' => $buktiPath,
                'jam_masuk'     => null,
                'jam_pulang'    => null,
            ]
        );

        return redirect()->route('mahasiswa.dashboard')
                         ->with('success', 'Ketidakhadiran berhasil dilaporkan.');
    }

    /**
     * Riwayat Presensi.
     * Satu-satunya sumber untuk data riwayat kehadiran — jangan duplikat
     * logic ini di controller lain.
     */
    public function riwayat(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $bulan     = $request->get('bulan', now()->format('Y-m'));

        [$tahun, $bulanNum] = explode('-', $bulan);

        $presensi = $mahasiswa->presensis()
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->orderBy('tanggal', 'desc')
            ->paginate(20)
            ->withQueryString();

        $statistik = $mahasiswa->statistikBulan((int) $tahun, (int) $bulanNum);

        // Daftar bulan sejak mulai magang, untuk dropdown filter
        $daftarBulan = collect();
        $cursor = Carbon::parse($mahasiswa->tanggal_mulai)->startOfMonth();
        while ($cursor->lte(now())) {
            $daftarBulan->put($cursor->format('Y-m'), $cursor->translatedFormat('F Y'));
            $cursor->addMonth();
        }

        return view('mahasiswa.riwayat', compact('presensi', 'statistik', 'daftarBulan'));
    }
}