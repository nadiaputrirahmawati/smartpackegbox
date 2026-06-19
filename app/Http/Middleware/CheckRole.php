<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Pastikan user sudah login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // 2. Cek apakah role user SESUAI dengan role yang diizinkan untuk halaman ini
        if (auth()->user()->role !== $role) {
            // Jika Admin nyasar ke halaman User, kembalikan ke dashboard admin
            if (auth()->user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            // Jika User nyasar ke halaman Admin, kembalikan ke dashboard user
            return redirect('/user/dashboard'); 
            
            // Opsional: Anda juga bisa menampilkan error 403 (Akses Ditolak)
            // abort(403, 'Akses Ditolak. Anda tidak memiliki izin ke halaman ini.');
        }

        // Jika lolos pengecekan, izinkan masuk!
        return $next($request);
    }
}