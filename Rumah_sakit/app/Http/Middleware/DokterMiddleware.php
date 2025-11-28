<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DokterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // DEBUG: Log dulu
        \Log::info('DokterMiddleware called for: ' . $request->path());
        
        if (auth()->check() && auth()->user()->isDokter()) {
            \Log::info('User is dokter: ' . auth()->user()->email);
            return $next($request);
        }

        \Log::info('User is NOT dokter or not logged in');
        return redirect('/dashboard')->with('error', 'Akses ditolak.');
    }
}