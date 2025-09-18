<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\UpdateLastSeen;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Reset Password
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Dashboard (satu halaman untuk semua role)
Route::middleware(['auth', UpdateLastSeen::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('teacher', TeacherController::class);
    Route::get('/admin/profile/edit', [AdminController::class, 'editAdminProfile'])->name('admin.edit');
    Route::put('/admin/profile/update', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/profile/photo', [AdminController::class, 'deletePhoto'])->name('admin.deletePhoto');
    Route::delete('/teacher/photo/{id}', [TeacherController::class, 'deletePhoto'])
        ->name('teacher.deletePhoto');
    Route::resource('student', StudentController::class);
    Route::put('student/update-inline/{id}', [StudentController::class, 'updateInline'])->name('student.updateInline');
    Route::get('/students/export-example', [StudentController::class, 'exportSampleCsv'])->name('students.exportSampleCsv');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/student/{id}/pdf', [StudentController::class, 'generatePdf'])->name('student.pdf');    // Rekap per tahun (PDF)
    Route::get('/rekap/{tahun}/pdf', [StudentController::class, 'rekapTahunanPdf'])->name('students.rekap');
    Route::get('/certificate', [StudentController::class, 'certificateIndex'])->name('students.certificate.index');
});
Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity.logs.index');
});
