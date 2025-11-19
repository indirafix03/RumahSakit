<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasienMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sudah login dan role-nya pasien
        if (auth()->check() && auth()->user()->isPasien()) {
            return $next($request);
        }

        // Jika bukan pasien, redirect ke dashboard dengan pesan error
        return redirect('/dashboard')->with('error', 'Akses ditolak. Hanya Pasien yang dapat mengakses halaman ini.');
    }
}