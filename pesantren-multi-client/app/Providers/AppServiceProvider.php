<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\TenantContext;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Daftarkan TenantContext agar bisa dipanggil di Controller
        $this->app->bind(TenantContext::class, function () {
            return new class implements TenantContext {
                public function id(): int
                {
                    return auth()->check() ? (auth()->user()->tenant_id ?? 1) : 1;
                }
            };
        });
    }

    public function boot(): void
    {
        //
    }
}