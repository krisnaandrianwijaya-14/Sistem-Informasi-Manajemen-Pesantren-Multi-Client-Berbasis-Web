<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Platform\Middleware\App\Modules\Platform\Http\Middleware\SetClientContext;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
        // Daftarkan route dari folder modul di sini
        Route::middleware('web')
            ->group(base_path('app/Modules/Academic/routes/web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
            $middleware->alias([
        'client' => \App\Modules\Platform\Http\Middleware\SetClientContext::class,
        'platform_staff' => \App\Modules\Platform\Http\Middleware\EnsureIsPlatformStaff::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
