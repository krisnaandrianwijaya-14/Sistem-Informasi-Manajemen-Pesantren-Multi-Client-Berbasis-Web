<?php

namespace App\Modules\Client;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class ClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding kontrak -> implementasi (diisi di modul lanjut)
    }

    public function boot(): void
    {
        // Daftarkan namespace view 'client::' → app/Modules/Client/resources/views/
        $this->loadViewsFrom(__DIR__.'/resources/views', 'client');

        // Daftarkan komponen Blade anonim khusus modul Client
        // (kalau folder resources/views/components belum ada, boleh komentar)
        // Blade::anonymousComponentPath(__DIR__.'/resources/views/components', 'client');
    }
}
