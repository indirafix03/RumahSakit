<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DokterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sudah login dan role-nya dokter
        if (auth()->check() && auth()->user()->isDokter()) {
            return $next($request);
        }

        // Jika bukan dokter, redirect ke dashboard dengan pesan error
        return redirect('/dashboard')->with('error', 'Akses ditolak. Hanya Dokter yang dapat mengakses halaman ini.');
    }
}