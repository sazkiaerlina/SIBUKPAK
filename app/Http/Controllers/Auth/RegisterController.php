<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman form pembuatan akun
     */
    public function create()
    {
        // Pastikan Anda membuat file view ini di resources/views/auth/register.blade.php
        return view('auth.register'); 
    }

    /**
     * Memproses data pembuatan akun
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang diinputkan pengguna
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Mewajibkan ketik ulang password
        ], [
            // Pesan error custom (opsional)
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // 2. Simpan data ke tabel users
        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'mahasiswa', // Otomatis diset sebagai mahasiswa
            'is_active' => 0            // 🔒 Dikunci! Menunggu ACC dari Admin BPS Ogan Ilir
        ]);

        // 3. Kembalikan ke halaman login dengan membawa pesan sukses
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan tunggu verifikasi dari Admin BPS sebelum Anda bisa login.');
    }
}