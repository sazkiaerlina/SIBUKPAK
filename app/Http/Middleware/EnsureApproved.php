<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user->bolehLogin()) {
            return redirect($user->redirectPath())->withErrors([
                'email' => 'Akun kamu belum aktif.',
            ]);
        }

        return $next($request);
    }
}