<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountStillActive
{
    /**
     * Dipasang HANYA di halaman-halaman untuk mahasiswa yang sudah diterima
     * (dashboard, presensi, profil, arsip) — BUKAN di /daftar atau /riwayat,
     * karena di sana is_active = false itu wajar (masih proses pendaftaran).
     *
     * Kalau admin menonaktifkan akun seseorang yang sedang login, middleware
     * ini akan memutus sesinya di request berikutnya, bukan menunggu dia
     * logout/login ulang.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->bolehLogin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun kamu telah dinonaktifkan oleh admin. Silakan hubungi admin BPS jika ini keliru.');
        }

        return $next($request);
    }
}