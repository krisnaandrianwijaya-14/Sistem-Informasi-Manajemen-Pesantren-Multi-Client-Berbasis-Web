<?php

namespace App\Modules\Academic;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AcademicServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Daftarkan namespace Livewire 'academic::'
        Livewire::addNamespace(
            namespace: 'academic',
            viewPath: __DIR__ . '/resources/views/livewire'
        );

        // Daftarkan folder view modul
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'academic');
    }
}