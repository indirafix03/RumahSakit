<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register alias middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'dokter' => \App\Http\Middleware\DokterMiddleware::class,
            'pasien' => \App\Http\Middleware\PasienMiddleware::class,
        ]);

        // Middleware groups (jika perlu modifikasi)
        $middleware->web(append: [
            // Tambahkan middleware lain jika diperlukan
        ]);

        $middleware->api(prepend: [
            // Untuk API routes
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();