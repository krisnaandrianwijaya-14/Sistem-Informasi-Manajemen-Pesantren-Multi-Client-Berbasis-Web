<?php

use App\Modules\Platform\Support\PortalRoutes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (in_array($user->role, ['platform_admin', 'finance_admin', 'support_specialist', 'saas_auditor'])) {
        return redirect()->route('admin.dashboard');
    }

    $client = $user->clients()->first();

    if ($client) {
        $tenantRole = $client->pivot->role ?? $user->role;

        if ($tenantRole === 'guru' || $user->role === 'guru') {
            return redirect("/{$client->slug}/guru/dashboard");
        }

        return redirect("/{$client->slug}/dashboard");
    }

    return redirect('/');
})->name('dashboard');

require __DIR__.'/settings.php';

PortalRoutes::admin(function () {
    Route::view('/dashboard', 'platform::dashboard')->name('dashboard');
    Route::get('/clients', fn() => view('platform::clients'))->name('clients.index');
    Route::get('/clients/{client}', fn(\App\Models\Client $client) => view('platform::client-detail', ['client' => $client]))->name('clients.show');
    Route::get('/users', fn() => view('platform::users'))->name('users.index');
    Route::get('/audit-logs', fn() => view('platform::audit'))->name('audit.index');
});

// Rute Dashboard Admin Pesantren, Akademik & Guru
PortalRoutes::client(function () {
    require __DIR__.'/client.php';

    Route::prefix('guru')->name('guru.')->group(function () {
        require __DIR__.'/guru.php';
    });
});

Route::middleware(['auth'])->get('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout.get');
