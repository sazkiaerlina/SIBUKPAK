<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/admin/home', [AdminController::class, 'home']);

Route::get('/admin/mahasiswa', [AdminController::class, 'mahasiswa']);

Route::get('/admin/kelola-pendaftar', [AdminController::class, 'kelolaPendaftar']);

Route::get('/admin/sertifikat', [AdminController::class, 'sertifikat']);

Route::get('/admin/mahasiswa/{id}', [AdminController::class, 'detailmahasiswa']);

Route::get('/admin/mahasiswa/{id}/edit', [AdminController::class,'editmahasiswa']);

Route::put('/admin/mahasiswa/{id}', [AdminController::class,'updatemahasiswa']);

Route::get('/admin/rekap', [AdminController::class, 'rekap']);

Route::delete('/admin/mahasiswa/{id}', [AdminController::class, 'hapusmahasiswa']);