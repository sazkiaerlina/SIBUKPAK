<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Presensi;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\User;



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

    $totalTerlambat = (clone $queryStatistik)
                    ->where('status', 'terlambat')
                    ->count();

    $totalIzinSakit = (clone $queryStatistik)
                    ->whereIn('status', ['izin', 'sakit'])
                    ->count();

    return view('admin.home', compact(
        'presensiHariIni',
        'totalMahasiswa',
        'totalHadir',
        'totalTerlambat',
        'totalIzinSakit',
        'periode'
    ));
}



public function mahasiswa(Request $request)
{
    $keyword = $request->keyword;

    $mahasiswas = Mahasiswa::with('user')
        ->when($keyword, function ($query) use ($keyword) {

            $query->where('nim', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function ($q) use ($keyword) {
                      $q->where('name', 'like', "%{$keyword}%");
                  });

        })
        ->paginate(10)
        ->withQueryString();

    return view('admin.mahasiswa', compact('mahasiswas', 'keyword'));
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


public function updateMahasiswa(Request $request, $id)
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

    $mahasiswa->user->update([
        'name' => $request->name,
        'email' => $request->email,
        'is_active' => $request->is_active,
    ]);

    $mahasiswa->update([
        'nim' => $request->nim,
        'jurusan' => $request->jurusan,
        'prodi' => $request->prodi,
        'fakultas' => $request->fakultas,
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
    // 1. Cari data mahasiswa (Anak 1)
    $mahasiswa = Mahasiswa::findOrFail($id);
    
    // 2. Ambil data akun loginnya (Induk Utama)
    $user = $mahasiswa->user;

    // 3. HAPUS CUCU: Hapus semua riwayat presensi milik mahasiswa ini
    $mahasiswa->presensis()->delete(); 

    // 4. HAPUS ANAK 1: Hapus biodata mahasiswanya
    $mahasiswa->delete();

    // Pastikan user-nya ada sebelum menghapus relasi lainnya
    if ($user) {
        
        // 5. HAPUS ANAK 2: Hapus sertifikat jika ada di tabel certificates
        if ($user->certificate) {
            $user->certificate->delete();
        }

        // 6. HAPUS INDUK: Terakhir, baru hapus akun loginnya
        $user->delete();
    }

    return redirect('/admin/mahasiswa')
            ->with('success', 'Data mahasiswa, presensi, beserta sertifikatnya berhasil dihapus permanen.');
}

/**
 * Rekap Presensi.
 * Filter disederhanakan jadi 4 pilihan: hari_ini, bulan, custom (tanggal
 * bebas), semua. Ditambah pencarian nama/NIM mahasiswa.
 */
public function rekap(Request $request)
{
    $periode = $request->periode ?? 'hari_ini';
    $tanggalDari = $request->tanggal_dari ?? today()->format('Y-m-d');
    $tanggalSampai = $request->tanggal_sampai ?? today()->format('Y-m-d');
    $keyword = $request->keyword;
 
    $queryTabel = Presensi::with('mahasiswa.user');
 
    switch ($periode) {
 
        case 'bulan':
            $queryTabel->whereMonth('tanggal', now()->month)
                       ->whereYear('tanggal', now()->year);
            break;
 
        case 'custom':
            // Jaga-jaga kalau admin kebalik isi tanggal (dari > sampai), otomatis ditukar
            if ($tanggalDari > $tanggalSampai) {
                [$tanggalDari, $tanggalSampai] = [$tanggalSampai, $tanggalDari];
            }
            $queryTabel->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
            break;
 
        case 'semua':
            // tidak difilter tanggal
            break;
 
        default: // hari_ini
            $queryTabel->whereDate('tanggal', today());
            break;
    }
 
    // ── Pencarian nama / NIM mahasiswa ──────────────────────
    if ($keyword) {
        $queryTabel->whereHas('mahasiswa', function ($q) use ($keyword) {
            $q->where('nim', 'like', "%{$keyword}%")
              ->orWhereHas('user', function ($q2) use ($keyword) {
                  $q2->where('name', 'like', "%{$keyword}%");
              });
        });
    }
 
    $presensiRekap = $queryTabel
        ->orderBy('tanggal', 'desc')
        ->paginate(10)
        ->withQueryString();
 
    return view('admin.rekap', compact(
        'presensiRekap',
        'periode',
        'tanggalDari',
        'tanggalSampai',
        'keyword'
    ));
}


public function export(Request $request)
{
    $periode = $request->periode ?? 'hari_ini';
    $tanggalDari = $request->tanggal_dari ?? today()->format('Y-m-d');
    $tanggalSampai = $request->tanggal_sampai ?? today()->format('Y-m-d');
    $keyword = $request->keyword;

    $query = Presensi::with('mahasiswa.user');

    switch ($periode) {

        case 'bulan':

            $query->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);

            break;

        case 'custom':

            if ($tanggalDari > $tanggalSampai) {
                [$tanggalDari, $tanggalSampai] =
                [$tanggalSampai, $tanggalDari];
            }

            $query->whereBetween('tanggal', [
                $tanggalDari,
                $tanggalSampai
            ]);

            break;

        case 'semua':

            break;

        default:

            $query->whereDate('tanggal', today());

            break;
    }

    // Filter pencarian nama/NIM
    if ($keyword) {

        $query->whereHas('mahasiswa', function ($q) use ($keyword) {

            $q->where('nim', 'like', "%{$keyword}%")
              ->orWhereHas('user', function ($q2) use ($keyword) {

                  $q2->where('name', 'like', "%{$keyword}%");

              });

        });

    }

    $data = $query->orderBy('tanggal', 'desc')->get();

    $response = new StreamedResponse(function () use ($data) {

        $handle = fopen('php://output', 'w');

        fputcsv($handle, [
            'No',
            'Nama Mahasiswa',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status'
        ]);

        $no = 1;

        foreach ($data as $item) {

            fputcsv($handle, [
                $no++,
                $item->mahasiswa->user->name,
                $item->tanggal,
                $item->jam_masuk,
                $item->jam_pulang,
                $item->status
            ]);

        }

        fclose($handle);
    });

    $filename = 'rekap_presensi_' . now()->format('Ymd_His') . '.csv';

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set(
        'Content-Disposition',
        'attachment; filename="'.$filename.'"'
    );

    return $response;
}


public function profil()
{
    return view('admin.profil');
}


public function updateEmail(Request $request)
{
    $request->validate([
        'current_email' => ['required', 'email'],
        'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore(Auth::id()),
        ],
    ]);

    $user = User::findOrFail(Auth::id());

    // Cek email lama
    if ($request->current_email != $user->email) {
        return back()->withErrors([
            'current_email' => 'Email saat ini tidak sesuai.'
        ]);
    }

    // Update email baru
    $user->update([
        'email' => $request->email,
    ]);

    return back()->with('success', 'Email berhasil diperbarui.');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password'=>'required',
        'password'=>'required|min:8|confirmed',
    ]);

            $user = User::findOrFail(Auth::id());

    if(!Hash::check($request->current_password,$user->password)){
        return back()->withErrors([
            'current_password'=>'Password saat ini salah.'
        ]);
    }

   $user->update([
    'password' => Hash::make($request->password),
]);

    return back()->with('success','Password berhasil diubah.');
}




public function kelolaPendaftar()
{
    return view('admin.kelola-pendaftar');
}

public function sertifikat()
{
    return view('admin.sertifikat');
}

public function lihatBuktiPresensi(Presensi $presensi)
{
    abort_unless($presensi->bukti_dokumen, 404, 'Bukti dokumen tidak ada.');
    abort_unless(
        Storage::disk('public')->exists($presensi->bukti_dokumen),
        404,
        'File bukti dokumen tidak ditemukan.'
    );
 
    return response()->file(
        Storage::disk('public')->path($presensi->bukti_dokumen)
    );

}

}