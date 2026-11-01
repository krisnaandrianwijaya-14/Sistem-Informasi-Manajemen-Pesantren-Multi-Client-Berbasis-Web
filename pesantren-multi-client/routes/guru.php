<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('client.guru.dashboard');
})->name('dashboard');

// Rute Absensi
Route::get('/schedules', [\App\Http\Controllers\Guru\AttendanceController::class, 'index'])->name('attendances.index');
Route::get('/schedules/{schedule}/attendance', [\App\Http\Controllers\Guru\AttendanceController::class, 'create'])->name('attendances.create');
Route::post('/schedules/{schedule}/attendance', [\App\Http\Controllers\Guru\AttendanceController::class, 'store'])->name('attendances.store');