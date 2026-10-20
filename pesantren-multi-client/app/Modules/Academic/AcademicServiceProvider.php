<?php

namespace App\Modules\Academic;

use App\Modules\ModuleServiceProvider;

class AcademicServiceProvider extends ModuleServiceProvider
{
    protected function moduleAlias(): string
    {
        return 'academic';
    }

    // Tambahkan fungsi boot ini untuk memaksa pendaftaran view
    public function boot(): void
    {
        // Daftarkan view secara eksplisit menggunakan __DIR__
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'academic');

        // Jalankan fungsi boot bawaan (untuk route dan livewire)
        parent::boot();
    }
}
