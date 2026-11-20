<?php
use Illuminate\Support\Facades\Route;

Route::get('/academic/students', fn () => view('academic::admin.students'))->name('academic.students');