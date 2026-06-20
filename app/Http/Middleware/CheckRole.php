<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Mengecek apakah role user saat ini TIDAK SAMA dengan role yang diizinkan
        if (auth()->user()->role !== $role) {
            // Jika tidak sama, tampilkan halaman error 403 (Forbidden / Akses Ditolak)
            abort(403);
        }

        // Jika sama, lanjutkan request ke proses selanjutnya
        return $next($request);
    }
}