<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\VerifikasiPendaftaranController;
use App\Http\Controllers\Admin\LaporanSertifikatController;
use App\Http\Controllers\Admin\SertifikatSettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\PresensiController;
use App\Http\Controllers\Mahasiswa\LaporanController;
use App\Http\Controllers\Mahasiswa\ProfilController;


// ── Halaman Utama ────────────────────────────────────────
Route::get('/', function () {
    return view('landing');
})->name('home');

// ── Autentikasi (Login & Register) ───────────────────────
// Hanya bisa diakses TAMU (belum login). Kalau sudah login,
// otomatis dilempar ke redirectPath() lewat RedirectIfAuthenticated.
Route::middleware(['guest', 'preventBackHistory'])->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');

    
});

// Logout hanya untuk yang sudah login
Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

// ── Pendaftaran Magang (Satu Pintu Utama) ────────────────
Route::middleware('auth')->group(function () {
    Route::get('/daftar', [PendaftaranController::class, 'create'])->name('daftar.create');
    Route::post('/daftar', [PendaftaranController::class, 'store'])->name('daftar.store');
    Route::get('/daftar/sukses/{mahasiswa}', [PendaftaranController::class, 'riwayat'])
        ->name('daftar.sukses');
});

// ══════════════════════════════════════════════════════════════
//  AREA MAHASISWA — wajib login DAN sudah disetujui admin
//  (middleware 'approved' menolak akses kalau status masih pending)
// ══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'approved', 'mahasiswa', 'still-active'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

    // ── Dashboard ──────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Presensi (absen masuk/pulang) ─────────────────────
    Route::post('/absen/masuk', [PresensiController::class, 'absenMasuk'])->name('absen.masuk');
    Route::post('/absen/pulang', [PresensiController::class, 'absenPulang'])->name('absen.pulang');

    // ── Ketidakhadiran (izin/sakit) ────────────────────────
    Route::get('/ketidakhadiran', [PresensiController::class, 'formKetidakhadiran'])->name('ketidakhadiran.form');
    Route::post('/ketidakhadiran', [PresensiController::class, 'submitKetidakhadiran'])->name('ketidakhadiran.store');

    // ── Riwayat Kehadiran ───────────────────────────────────
    Route::get('/riwayat', [PresensiController::class, 'riwayat'])->name('riwayat');

    // ── Laporan & Sertifikat ────────────────────────────────
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/sertifikat', [LaporanController::class, 'downloadSertifikat'])->name('sertifikat.download');

    // ── Profil ──────────────────────────────────────────────
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});


// ══════════════════════════════════════════════════════════════
//  AREA ADMIN
// ══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {



    Route::get('/home', [AdminController::class, 'home'])->name('home');
    Route::get('/kelola-pendaftar', [AdminController::class, 'kelolaPendaftar'])->name('kelola-pendaftar');
    Route::get('/rekap', [AdminController::class, 'rekap'])->name('rekap');
    Route::get('/rekap/export', [AdminController::class, 'export'])->name('rekap.export');
      Route::get('/profil', [AdminController::class, 'profil'])->name('profil');
    Route::put('/profil/email', [AdminController::class, 'updateEmail'])->name('profil.email');
    Route::put('/profil/password', [AdminController::class, 'updatePassword'])->name('profil.password');

    Route::get('/mahasiswa', [AdminController::class, 'mahasiswa'])->name('mahasiswa.index');
    Route::get('/mahasiswa/{id}', [AdminController::class, 'detailmahasiswa'])->name('mahasiswa.show');
    Route::get('/mahasiswa/{id}/edit', [AdminController::class, 'editmahasiswa'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{id}', [AdminController::class, 'updatemahasiswa'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [AdminController::class, 'hapusmahasiswa'])->name('mahasiswa.destroy');

    Route::get('/verifikasi', [VerifikasiPendaftaranController::class, 'index'])->name('verifikasi.index');
    Route::get('/verifikasi/{mahasiswa}', [VerifikasiPendaftaranController::class, 'show'])->name('verifikasi.show');
    Route::patch('/verifikasi/{mahasiswa}/approve', [VerifikasiPendaftaranController::class, 'approve'])->name('verifikasi.approve');
    Route::patch('/verifikasi/{mahasiswa}/reject', [VerifikasiPendaftaranController::class, 'reject'])->name('verifikasi.reject');

    // ── Laporan & Sertifikat ────────────────────────────────
    Route::get('/laporan-sertifikat', [LaporanSertifikatController::class, 'index'])->name('laporan.index');
    Route::patch('/laporan-sertifikat/{mahasiswa}/simpan', [LaporanSertifikatController::class, 'simpanSertifikat'])->name('laporan.sertifikat.simpan');
    Route::get('/laporan-sertifikat/{mahasiswa}/download', [LaporanSertifikatController::class, 'downloadSertifikat'])->name('laporan.sertifikat.download');

    // ── Pengaturan Template Sertifikat ──────────────────────
    Route::get('/sertifikat-setting', [SertifikatSettingController::class, 'edit'])->name('sertifikat-setting.edit');
    Route::put('/sertifikat-setting', [SertifikatSettingController::class, 'update'])->name('sertifikat-setting.update');
    Route::get('/sertifikat-setting/preview', [SertifikatSettingController::class, 'preview'])->name('sertifikat-setting.preview');
});