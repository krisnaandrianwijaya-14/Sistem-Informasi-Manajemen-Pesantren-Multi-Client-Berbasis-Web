<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Platform\Support\PortalRoutes;


Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();

        // Jika dia staf platform, lempar ke Admin Dashboard
        if (in_array($user->role, ['platform_admin', 'finance_admin', 'support_specialist', 'saas_auditor'])) {
            return redirect()->route('admin.dashboard');
        }

        // Jika dia user biasa (Admin Pesantren), cari pesantrennya
        $client = $user->clients()->first();

        if ($client) {
            return redirect("/{$client->slug}/dashboard"); // Lempar ke halaman dummy
        }

        return redirect('/');
    })->name('dashboard');
});

require __DIR__.'/settings.php';

PortalRoutes::admin(function () {
    Route::view('/dashboard', 'platform::dashboard')->name('dashboard');
    Route::get('/clients', fn() => view('platform::clients'))->name('clients.index');
    Route::get('/clients/{client}', fn(\App\Models\Client $client) => view('platform::client-detail', ['client' => $client]))->name('clients.show');
    Route::get('/users', fn() => view('platform::users'))->name('users.index');
    Route::get('/audit-logs', fn() => view('platform::audit'))->name('audit.index');
});

// Rute sementara untuk Dashboard Pesantren (Dikerjakan Orang B & C nanti)
PortalRoutes::client(function () {
    Route::get('/dashboard', function () {
        $client = app(\App\Modules\Platform\Support\ClientContext::class)->get();
        return view('platform::tenant-dummy', ['client' => $client]);
    })->name('dashboard');
});
