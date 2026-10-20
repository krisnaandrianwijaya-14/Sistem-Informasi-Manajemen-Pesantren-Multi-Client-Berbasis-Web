<?php

use Illuminate\Support\Facades\Route;

// Ini hanya untuk tes apakah route modul terbaca
Route::get('/tes-modul-academic', function () {
    return view('academic::halo');
})->name('tes.academic');

Route::middleware('web')->group(function () {
    Route::get('/tes-modul-academic', function () {
        return view('academic::halo');
    })->name('tes.academic');
});
