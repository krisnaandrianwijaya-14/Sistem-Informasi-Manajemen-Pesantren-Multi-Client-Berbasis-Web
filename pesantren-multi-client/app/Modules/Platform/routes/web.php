<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Platform\Support\PortalRoutes;

// app/Modules/Platform/routes/web.php

// 1. Rute Publik (Landing Page SiSantri & Profil Pesantren)
PortalRoutes::public(function () {
    // Landing Page Pusat
    Route::get('/', fn() => view('platform::public.landing'))->name('home');

    // Website Profil Pesantren (Dinamis berdasarkan slug)
    Route::get('/{client:slug}', function (\App\Models\Client $client) {
        return view('platform::public.tenant-profile', ['client' => $client]);
    })->name('tenant.profile');
});

// 2. Rute Admin (Superadmin)
PortalRoutes::admin(function () {
    Route::view('/dashboard', 'platform::dashboard')->name('dashboard');
    Route::get('/clients', fn() => view('platform::clients'))->name('clients.index');
    Route::get('/clients/{client}', fn(\App\Models\Client $client) => view('platform::client-detail', ['client' => $client]))->name('clients.show');
    Route::get('/users', fn() => view('platform::users'))->name('users.index');
    Route::get('/audit-logs', fn() => view('platform::audit'))->name('audit.index');
});

// 3. Rute Tenant / Pesantren (Dashboard)
PortalRoutes::client(function () {
    Route::get('/dashboard', function () {
        $client = app(\App\Modules\Platform\Support\ClientContext::class)->get();
        return view('platform::tenant-dummy', ['client' => $client]);
    })->name('dashboard');
});
