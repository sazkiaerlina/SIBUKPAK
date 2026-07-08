<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Penggunaan di Route: ->middleware(['auth', 'role:admin,mahasiswa'])
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Ambil data user yang sedang login
        $user = $request->user();

        // 2. Pengecekan Role
        if (! $user || ! in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // 3. Failsafe (Lapis Keamanan Ekstra) untuk akun tidak aktif
        if (! $user->is_active) {
            auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi dihentikan. Akun Anda telah dinonaktifkan oleh Admin.']);
        }

        return $next($request);
    }
}