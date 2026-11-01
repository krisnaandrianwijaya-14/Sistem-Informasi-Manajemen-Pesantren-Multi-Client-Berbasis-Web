<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('client.admin.dashboard');
})->name('dashboard');

Route::get('/test-scope/students/{student}', function (\App\Models\Client $client, \App\Models\Student $student) {
    return response()->json([
        'client_slug' => $client->slug,
        'student_name' => $student->name,
        'student_client' => $student->client_id,
        'match' => $student->client_id === $client->id,
    ]);
});

Route::resource('classes', \App\Http\Controllers\Admin\SchoolClassController::class);
Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('schedules', \App\Http\Controllers\Admin\ScheduleController::class);





