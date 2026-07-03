<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Presensi;

class AdminController extends Controller
{


public function home(Request $request)
{
    $periode = $request->periode ?? 'hari_ini';

    // Query untuk tabel
    $queryTabel = Presensi::with('mahasiswa.user');

    // Query untuk statistik
    $queryStatistik = Presensi::query();

    switch ($periode) {

        case '7_hari':

            $queryTabel->whereDate('tanggal', '>=', now()->subDays(6));
            $queryStatistik->whereDate('tanggal', '>=', now()->subDays(6));

            break;

        case '30_hari':

            $queryTabel->whereDate('tanggal', '>=', now()->subDays(29));
            $queryStatistik->whereDate('tanggal', '>=', now()->subDays(29));

            break;

        case 'bulan':

            $queryTabel->whereMonth('tanggal', now()->month)
                       ->whereYear('tanggal', now()->year);

            $queryStatistik->whereMonth('tanggal', now()->month)
                           ->whereYear('tanggal', now()->year);

            break;

        case 'tahun':

            $queryTabel->whereYear('tanggal', now()->year);
            $queryStatistik->whereYear('tanggal', now()->year);

            break;

        case 'semua':

            // tidak difilter

            break;

        default:

            $queryTabel->whereDate('tanggal', today());
            $queryStatistik->whereDate('tanggal', today());

            break;
    }

    // Data tabel
    $presensiHariIni = $queryTabel
        ->orderBy('tanggal', 'desc')
        ->paginate(10)
        ->withQueryString();

    // Statistik
    $totalMahasiswa = Mahasiswa::count();

    $totalHadir = (clone $queryStatistik)
                    ->where('status', 'hadir')
                    ->count();

    $totalAlpa = (clone $queryStatistik)
                    ->where('status', 'alpa')
                    ->count();

    $totalIzinSakit = (clone $queryStatistik)
                    ->whereIn('status', ['izin', 'sakit'])
                    ->count();

    return view('admin.home', compact(
        'presensiHariIni',
        'totalMahasiswa',
        'totalHadir',
        'totalAlpa',
        'totalIzinSakit',
        'periode'
    ));
}


public function mahasiswa()

{
    $mahasiswas = Mahasiswa::with('user')->get();

return view('admin.mahasiswa', compact('mahasiswas'));
}

public function detailMahasiswa($id)
{
    $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

return view('admin.detail-mahasiswa', compact('mahasiswa'));
}

public function editMahasiswa($id)
{
    $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

return view('admin.edit-mahasiswa', compact('mahasiswa'));
}

public function updateMahasiswa(Request $request,$id)
{
    $request->validate([
        'name' => 'required|max:100',
        'email' => 'required|email|max:100',
        'nim' => 'required|max:20',
        'jurusan' => 'required|max:100',
        'universitas' => 'required|max:150',
        'nomor_hp' => 'nullable|max:20',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        'is_active' => 'required'
    ]);

    $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

    // Update tabel users
    $mahasiswa->user->update([
        'name' => $request->name,
        'email' => $request->email,
        'is_active' => $request->is_active,
    ]);

    // Update tabel mahasiswas
    $mahasiswa->update([
        'nim' => $request->nim,
        'jurusan' => $request->jurusan,
        'universitas' => $request->universitas,
        'nomor_hp' => $request->nomor_hp,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
    ]);
    
    return redirect('/admin/mahasiswa')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
}

public function hapusMahasiswa($id)
{
    $mahasiswa = Mahasiswa::findOrFail($id);

    $user = $mahasiswa->user;

    $user->delete();

    return redirect('/admin/mahasiswa')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
}

  public function rekap(Request $request)
{
    $periode = $request->periode ?? 'hari_ini';

    $queryTabel = Presensi::with('mahasiswa.user');

    switch ($periode) {

        case '7_hari':

            $queryTabel->whereDate('tanggal', '>=', now()->subDays(6));

            break;

        case '30_hari':

            $queryTabel->whereDate('tanggal', '>=', now()->subDays(29));

            break;

        case 'bulan':

            $queryTabel->whereMonth('tanggal', now()->month)
                       ->whereYear('tanggal', now()->year);

            break;

        case 'tahun':

            $queryTabel->whereYear('tanggal', now()->year);

            break;

        case 'semua':

            // tidak difilter

            break;

        default:

            $queryTabel->whereDate('tanggal', today());

            break;
    }

    $presensiRekap = $queryTabel
        ->orderBy('tanggal', 'desc')
        ->paginate(10)
        ->withQueryString();

    return view('admin.rekap', compact(
        'presensiRekap',
        'periode'
    ));
}

public function kelolaPendaftar()
{
    return view('admin.kelola-pendaftar');
}

public function sertifikat()
{
    return view('admin.sertifikat');
}


}