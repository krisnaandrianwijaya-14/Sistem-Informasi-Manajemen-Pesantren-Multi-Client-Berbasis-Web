<?php
use App\Support\Routing\PortalRoutes;
use Illuminate\Support\Facades\Route;

PortalRoutes::admin(function () {
    Route::get('/academic/students', fn () => view('academic::admin.students'))->name('academic.students');
});