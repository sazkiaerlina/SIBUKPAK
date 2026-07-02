<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('landing');
})->name('home');

// Menampilkan halaman login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/kirim', function () {
    return view('create');
})->name('create');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/admin/home', [AdminController::class, 'home']);

Route::get('/admin/mahasiswa', [AdminController::class, 'mahasiswa']);

Route::get('/admin/kelola-pendaftar', [AdminController::class, 'kelolaPendaftar']);

Route::get('/admin/sertifikat', [AdminController::class, 'sertifikat']);

Route::get('/admin/mahasiswa/{id}', [AdminController::class, 'detailmahasiswa']);

Route::get('/admin/mahasiswa/{id}/edit', [AdminController::class,'editmahasiswa']);

Route::put('/admin/mahasiswa/{id}', [AdminController::class,'updatemahasiswa']);

Route::get('/admin/rekap', [AdminController::class, 'rekap']);

Route::delete('/admin/mahasiswa/{id}', [AdminController::class, 'hapusmahasiswa']);

