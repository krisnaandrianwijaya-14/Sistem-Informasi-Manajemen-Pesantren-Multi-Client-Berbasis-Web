<?php

use App\Models\Schedule;
use App\Modules\Platform\Support\ClientContext;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/dashboard', function () {
    $client = app(ClientContext::class)->get();

    $schedules = Schedule::query()
        ->when($client && Schema::hasColumn('schedules', 'client_id'), function ($query) use ($client) {
            $query->where('client_id', $client->id);
        })
        ->get();

    return view('client.guru.dashboard', compact('schedules'));
})->name('dashboard');

// Rute Absensi
Route::get('/schedules', [\App\Http\Controllers\Guru\AttendanceController::class, 'index'])->name('attendances.index');
Route::get('/schedules/{schedule}/attendance', [\App\Http\Controllers\Guru\AttendanceController::class, 'create'])->name('attendances.create');
Route::post('/schedules/{schedule}/attendance', [\App\Http\Controllers\Guru\AttendanceController::class, 'store'])->name('attendances.store');
