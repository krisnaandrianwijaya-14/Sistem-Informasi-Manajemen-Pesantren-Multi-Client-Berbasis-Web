<?php

use App\Http\Controllers\FieldOpsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/kamtib', [FieldOpsController::class, 'kamtibPage'])->name('kamtib');
    Route::post('/kamtib/scan', [FieldOpsController::class, 'kamtibScan'])->name('kamtib.scan');

    Route::get('/asrama-scan', [FieldOpsController::class, 'asramaPage'])->name('asrama.scan');
    Route::post('/asrama-scan/absensi', [FieldOpsController::class, 'asramaAbsensi'])->name('asrama.absensi');
    Route::post('/asrama-scan/izin', [FieldOpsController::class, 'asramaIzin'])->name('asrama.izin');
});
