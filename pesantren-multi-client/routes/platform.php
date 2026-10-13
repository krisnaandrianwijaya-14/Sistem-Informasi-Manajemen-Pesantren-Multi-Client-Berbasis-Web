<?php

use App\Modules\Client\Http\Controllers\AdminClientBankController;
use App\Modules\Client\Http\Controllers\AdminClientController;
use Illuminate\Support\Facades\Route;

// Platform Admin — Dashboard
Route::get('/', function () {
    return view('platform.dashboard');
})->name('dashboard');

// Platform Admin — Manajemen Client (Modul 5)
Route::prefix('clients')->name('clients.')->group(function (): void {
    Route::get('/', [AdminClientController::class, 'index'])->name('index');
    Route::get('/create', [AdminClientController::class, 'create'])->name('create');
    Route::post('/', [AdminClientController::class, 'store'])->name('store');
    Route::get('/{client}', [AdminClientController::class, 'edit'])->name('edit');

    // Fee administrasi (effective-dated)
    Route::post('/{client}/fee', [AdminClientController::class, 'scheduleFee'])
        ->name('fee.store');

// UC-21 alur 3a — aktif/nonaktifkan client
Route::post('/{client}/status', [AdminClientController::class, 'changeStatus'])
    ->name('status');

    // Rekening bank
    Route::post('/{client}/bank', [AdminClientBankController::class, 'store'])
        ->name('bank.store');
    Route::post('/{client}/bank/{account}/verify', [AdminClientBankController::class, 'verify'])
        ->name('bank.verify');
    Route::post('/{client}/bank/{account}/primary', [AdminClientBankController::class, 'makePrimary'])
        ->name('bank.primary');
});
