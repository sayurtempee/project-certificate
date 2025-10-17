<?php

use App\Http\Middleware\AutoLogout;
use Illuminate\Foundation\Application;
use App\Http\Middleware\UpdateLastSeen;
use App\Http\Middleware\TeacherActivityLogger;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    // Tambahkan middleware global (aktif untuk semua route)
    $middleware->append([
        AutoLogout::class,
        UpdateLastSeen::class,
        TeacherActivityLogger::class,
    ]);

    $middleware->web([
        AutoLogout::class,
        TeacherActivityLogger::class,
        UpdateLastSeen::class,
        StartSession::class,
    ]);

    // Tambahkan alias agar bisa dipanggil di route jika perlu
    $middleware->alias([
        'auto.logout'   => AutoLogout::class,
        'update.lastseen' => UpdateLastSeen::class,
        'teacher.log'   => TeacherActivityLogger::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
