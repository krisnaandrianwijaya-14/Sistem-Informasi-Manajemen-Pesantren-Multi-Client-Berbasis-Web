<?php
namespace App\Modules\Platform\Support;

use Closure;
use Illuminate\Support\Facades\Route;

class PortalRoutes
{
    public static function admin(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'platform_staff'])
        ->prefix('admin')
        ->name('admin.')
        ->group($routes);
    }

    public static function client(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'client'])->prefix('{client:slug}')->name('client.')->scopeBindings()->group($routes);
    }
}
