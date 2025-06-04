<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Pastikan User model punya relasi 'role' dan kolom 'name' di model Role
            if ($user->role && $user->role->name) { 
                $roleName = $user->role->name;

                // Jika pengguna sudah login dan mengakses rute bernama 'dashboard' (rute umum Breeze)
                // ATAU jika mereka mencoba mengakses root '/' setelah login dan belum ada redirect lain.
                // Kita akan fokus pada redirect dari rute 'dashboard' Breeze.
                if ($request->routeIs('dashboard')) {
                    if ($roleName === 'Admin') {
                        return redirect()->route('admin.dashboard');
                    } elseif ($roleName === 'Teknisi') {
                        return redirect()->route('teknisi.dashboard');
                    } elseif ($roleName === 'Pelanggan') {
                        return redirect()->route('pelanggan.dashboard');
                    }
                    // Jika peran tidak cocok atau tidak ada redirect spesifik,
                    // biarkan ke halaman dashboard umum (jika ada, atau $next).
                    // Namun, karena kita ingin selalu redirect, idealnya semua peran tercakup.
                }
            }
        }
        return $next($request);
    }
}