<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceRecapController;

Route::get('/dashboard', function () {
    return view('client.admin.dashboard');
})->name('dashboard');

// ========== STUDENTS ==========
Route::get('students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students.index');
Route::get('students/create', [\App\Http\Controllers\Admin\StudentController::class, 'create'])->name('students.create');
Route::post('students', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('students.store');
Route::get('students/{id}/edit', [\App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('students.edit');
Route::get('students/{id}', [\App\Http\Controllers\Admin\StudentController::class, 'show'])->name('students.show');
Route::put('students/{id}', [\App\Http\Controllers\Admin\StudentController::class, 'update'])->name('students.update');
Route::delete('students/{id}', [\App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('students.destroy');

// ========== CLASSES ==========
Route::get('classes', [\App\Http\Controllers\Admin\SchoolClassController::class, 'index'])->name('classes.index');
Route::get('classes/create', [\App\Http\Controllers\Admin\SchoolClassController::class, 'create'])->name('classes.create');
Route::post('classes', [\App\Http\Controllers\Admin\SchoolClassController::class, 'store'])->name('classes.store');
Route::get('classes/{id}/edit', [\App\Http\Controllers\Admin\SchoolClassController::class, 'edit'])->name('classes.edit');
Route::put('classes/{id}', [\App\Http\Controllers\Admin\SchoolClassController::class, 'update'])->name('classes.update');
Route::delete('classes/{id}', [\App\Http\Controllers\Admin\SchoolClassController::class, 'destroy'])->name('classes.destroy');

// ========== TEACHERS ==========
Route::get('teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('teachers.index');
Route::get('teachers/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('teachers.create');
Route::post('teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('teachers.store');
Route::get('teachers/{id}/edit', [\App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('teachers.edit');
Route::put('teachers/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('teachers.update');
Route::delete('teachers/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'destroy'])->name('teachers.destroy');

// ========== SUBJECTS ==========
Route::get('subjects', [\App\Http\Controllers\Admin\SubjectController::class, 'index'])->name('subjects.index');
Route::get('subjects/create', [\App\Http\Controllers\Admin\SubjectController::class, 'create'])->name('subjects.create');
Route::post('subjects', [\App\Http\Controllers\Admin\SubjectController::class, 'store'])->name('subjects.store');
Route::get('subjects/{id}/edit', [\App\Http\Controllers\Admin\SubjectController::class, 'edit'])->name('subjects.edit');
Route::put('subjects/{id}', [\App\Http\Controllers\Admin\SubjectController::class, 'update'])->name('subjects.update');
Route::delete('subjects/{id}', [\App\Http\Controllers\Admin\SubjectController::class, 'destroy'])->name('subjects.destroy');

// ========== SCHEDULES ==========
Route::get('schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedules.index');
Route::get('schedules/create', [\App\Http\Controllers\Admin\ScheduleController::class, 'create'])->name('schedules.create');
Route::post('schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('schedules.store');
Route::delete('schedules/{id}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('schedules.destroy');

// ========== ATTENDANCE ==========
Route::get('attendance/{scheduleId}', [AttendanceController::class, 'create'])->name('attendance');
Route::post('attendance/{scheduleId}', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('attendance-recap', [AttendanceRecapController::class, 'index'])->name('attendance.recap');

// ========== TEST ROUTE (darurat) ==========
Route::get('test-detail/{id}', function ($id) {
    $student = \App\Models\Student::withoutGlobalScopes()->find($id);
    if (!$student) {
        return "Santri ID {$id} TIDAK ADA di database. Total santri: " . \App\Models\Student::withTrashed()->count();
    }
    return "KETEMU: ID={$student->id} | NIS={$student->nis} | Nama={$student->full_name}";
})->name('test.detail');