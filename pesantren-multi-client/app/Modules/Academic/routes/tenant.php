<?php

use App\Support\Routing\PortalRoutes;
use Illuminate\Support\Facades\Route;

PortalRoutes::tenant(function () {
    // Halaman untuk Guru melihat form absensi
    Route::get('/academic/attendance', function () {
        // Mengambil 1 ID jadwal secara acak/pertama untuk keperluan testing
        // Di aplikasi nyata, ID ini didapat dari daftar jadwal yang dipilih guru
        $scheduleId = \App\Models\Schedule::first()?->id ?? 1;
        
        return view('academic::tenant.attendance', ['scheduleId' => $scheduleId]);
    })->name('academic.attendance');
});