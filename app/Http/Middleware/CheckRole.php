<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Menerima parameter role dalam bentuk ...$roles (bisa banyak)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // 1. Super Admin ('admin') BOLEH AKSES SEMUANYA
        if ($user->role === 'admin') {
            return $next($request);
        }

        // 2. Jika role user ada di dalam daftar yang diizinkan
        // Contoh penggunaan di route: role:keuangan,akademik
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Jika ditolak
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        
        // Tampilkan halaman error 403 (Forbidden)
        abort(403, 'AKSES DITOLAK: Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}