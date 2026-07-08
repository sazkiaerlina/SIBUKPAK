<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswa       = Auth::user()->mahasiswa;
        $presensiHariIni = $mahasiswa?->presensiHariIni();

        $statistik = $mahasiswa
            ? $mahasiswa->statistikBulan(now()->year, now()->month)
            : ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'terlambat' => 0];

        // ── Status masa magang: belum mulai / aktif / sudah selesai ──
        // Menentukan apakah fitur absen & izin boleh diakses hari ini.
        $today        = Carbon::today();
        $sudahMulai   = $mahasiswa && $today->gte($mahasiswa->tanggal_mulai);
        $sudahSelesai = $mahasiswa && $today->gt($mahasiswa->tanggal_selesai);
        $masaAktif    = $sudahMulai && !$sudahSelesai;

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'presensiHariIni',
            'statistik',
            'masaAktif',
            'sudahMulai',
            'sudahSelesai'
        ));
    }
}