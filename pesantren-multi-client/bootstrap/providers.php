<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;

// Tambahkan use statement ini agar lebih rapi
use App\Modules\Academic\AcademicServiceProvider;
use App\Modules\Dormitory\DormitoryServiceProvider;
use App\Modules\Platform\PlatformServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,

    // Panggil class-nya di sini
    AcademicServiceProvider::class,
    DormitoryServiceProvider::class,
    PlatformServiceProvider::class,
];
