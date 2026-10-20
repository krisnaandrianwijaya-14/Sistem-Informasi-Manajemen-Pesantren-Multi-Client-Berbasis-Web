<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use ReflectionClass;

abstract class ModuleServiceProvider extends ServiceProvider
{
    abstract protected function moduleAlias(): string;

    public function boot(): void
    {
        $path = $this->modulePath();

        // 1. Daftarkan view (contoh: view('academic::halo'))
        if (is_dir($path . '/resources/views')) {
            $this->loadViewsFrom($path . '/resources/views', $this->moduleAlias());
        }

        // 2. Daftarkan Livewire
        if (is_dir($path . '/resources/views/livewire')) {
            Livewire::addNamespace(
                namespace: $this->moduleAlias(),
                viewPath: $path . '/resources/views/livewire',
            );
        }

        // 3. Daftarkan route
        foreach (glob($path . '/routes/*.php') ?: [] as $routeFile) {
            $this->loadRoutesFrom($routeFile);
        }
    }

    protected function modulePath(): string
    {
        // PERHATIKAN BARIS INI: Harus getFileName(), BUKAN getName()
        return dirname((new ReflectionClass($this))->getFileName());
    }
}
