<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  // Menerima satu atau lebih nama peran sebagai argumen
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            // Jika belum login, biarkan middleware 'auth' yang menanganinya (biasanya redirect ke login)
            return $next($request); 
        }

        $user = Auth::user();

        // Periksa apakah pengguna memiliki salah satu dari peran yang diizinkan
        // Pastikan relasi 'role' pada model User sudah benar
        if ($user->role && in_array($user->role->name, $roles)) {
            return $next($request); // Pengguna memiliki peran yang sesuai, lanjutkan request
        }

        // Jika pengguna tidak memiliki peran yang sesuai
        abort(403, 'UNAUTHORIZED ACTION.'); // Tampilkan error 403 Forbidden
    }
}