<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermitPdfController;
use App\Http\Controllers\Api\PermitController;

Route::get('/', function () {
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return match ($user->role) {
            'platform_admin'  => redirect('/platform'),
            'admin_pesantren' => redirect('/client/'.$user->defaultClientSlug().'/admin/dashboard'),
            'guru'            => redirect('/client/'.$user->defaultClientSlug().'/guru/dashboard'),
            'wali_asrama'     => redirect('/client/'.$user->defaultClientSlug().'/asrama/dashboard'),
            'wali_santri'     => redirect('/client/'.$user->defaultClientSlug().'/wali/dashboard'),
            'santri'          => redirect('/client/'.$user->defaultClientSlug().'/santri/dashboard'),
            default => abort(403, 'Role Anda tidak dikenali sistem. Hubungi Admin.'),
        };
    }

    return view('auth.login');
})->name('root');

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout.get');

Route::get('/qr-image/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);

    if (! file_exists($fullPath)) {
        abort(404, 'QR image not found');
    }

    return response()->file($fullPath);
})->where('path', '.*')->name('qr.image');

Route::get('/students/{student}/qr', function (\App\Models\Student $student) {
    if (! $student->qr_image_path) {
        abort(404, 'QR belum dibuat untuk santri ini');
    }

    $fullPath = storage_path('app/public/' . $student->qr_image_path);

    if (! file_exists($fullPath)) {
        abort(404, 'File QR tidak ditemukan di storage');
    }

    return response()->file($fullPath);
})->name('students.qr');

Route::get('/permits/{permit}/pdf', [PermitPdfController::class, 'show'])
    ->name('permits.pdf');

Route::middleware('auth:sanctum')->prefix('permits')->group(function () {
    Route::post('/initiate', [PermitController::class, 'initiate']);
    Route::post('/depart',   [PermitController::class, 'depart']);
    Route::post('/report',   [PermitController::class, 'report']);
});

use App\Http\Controllers\ScanController;

Route::middleware('auth')->group(function () {
    Route::get('/kasir', [ScanController::class, 'kasirPage'])->name('kasir');
    Route::post('/kasir/lookup', [ScanController::class, 'lookup'])->name('kasir.lookup');
    Route::post('/kasir/bayar', [ScanController::class, 'bayar'])->name('kasir.bayar');
    Route::get('/kartu/{student}', [ScanController::class, 'kartu'])->name('kartu');
});
require __DIR__.'/field_ops.php';
