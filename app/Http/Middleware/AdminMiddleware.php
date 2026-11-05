<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Proteksi Keamanan: Jika belum login atau bukan admin, langsung blokir dengan HTTP 403 Forbidden
        if (!Auth::check()) {
            abort(403, 'Akses Ditolak: Anda belum login! Area ini terproteksi ketat dan hanya boleh diakses oleh Administrator.');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Akun Anda terdaftar sebagai Pelanggan (User) dan tidak memiliki hak akses ke panel Administrator.');
        }

        return $next($request);
    }
}
