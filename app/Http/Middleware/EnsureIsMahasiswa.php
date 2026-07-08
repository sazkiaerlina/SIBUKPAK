<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsMahasiswa
{
    /**
     * Mencegah akun non-mahasiswa (misalnya admin) mengakses
     * halaman-halaman yang khusus untuk mahasiswa.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'mahasiswa') {
            // Kalau yang nyasar itu admin, lempar balik ke dashboard admin
            // supaya tidak bingung dapat halaman error.
            if ($user && $user->isAdmin()) {
                return redirect()->route('admin.home')
                    ->with('error', 'Halaman itu khusus untuk akun mahasiswa.');
            }

            abort(403, 'Halaman ini hanya untuk akun mahasiswa.');
        }

        return $next($request);
    }
}