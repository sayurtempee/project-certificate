<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\UpdateLastSeen;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CertificateController;

// Halaman login
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

// Batasi login: 5 percobaan per menit per IP
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])
    ->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->middleware('throttle:5,1') // batasi percobaan reset password
    ->name('password.email');

// Reset Password
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('throttle:5,1')
    ->name('password.update');

// Dashboard & fitur untuk semua role
Route::middleware(['auth', UpdateLastSeen::class, 'teacher.log', 'throttle:60,1'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Teacher
    Route::resource('teacher', TeacherController::class);
    Route::delete('/teacher/photo/{id}', [TeacherController::class, 'deletePhoto'])
        ->name('teacher.deletePhoto');

    // Admin
    Route::get('/admin/profile/edit', [AdminController::class, 'editAdminProfile'])->name('admin.edit');
    Route::put('/admin/profile/update', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/profile/photo', [AdminController::class, 'deletePhoto'])->name('admin.deletePhoto');

    // Student
    Route::resource('student', StudentController::class);
    Route::put('student/update-inline/{id}', [StudentController::class, 'updateInline'])->name('student.updateInline');
    Route::get('/students/export-example', [StudentController::class, 'exportSampleCsv'])->name('students.exportSampleCsv');
    Route::post('/students/import', [StudentController::class, 'import'])->middleware('throttle:10,1')->name('students.import');
    Route::get('/student/{id}/pdf', [StudentController::class, 'generatePdf'])->name('student.pdf');
    Route::get('/rekap/{tahun}/pdf', [StudentController::class, 'rekapTahunanPdf'])->name('students.rekap');

    // Certificate
    Route::get('/certificate', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificate/{id}', [CertificateController::class, 'showCertificate'])->name('certificates.showCertificate');
    Route::get('/certificate/{id}/download', [CertificateController::class, 'downloadCertificate'])->name('certificates.downloadCertificate');
    Route::post('/certificate/{id}/tanggalLulus', [CertificateController::class, 'updateTanggalLulus'])->name('certificates.updateTanggalLulus');
    Route::post('/certificate/{id}/tempatLulus', [CertificateController::class, 'updateTempatKelulusan'])->name('certificates.updateTempatKelulusan');
    Route::post('/certificate/{id}/nama-kepsek', [CertificateController::class, 'updateNamaKepsek'])->name('certificates.updateNamaKepsek');
    Route::post('/certificate/{id}/nip-kepsek', [CertificateController::class, 'updateNipKepsek'])->name('certificates.updateNipKepsek');
});

// Route khusus admin
Route::middleware(['auth', 'can:isAdmin', 'throttle:30,1'])->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity.logs.index');
    Route::get('/activity-logs/pdf', [ActivityLogController::class, 'backupPdf'])
        ->name('activity.logs.pdf');
    Route::delete('/activity-logs/clear', [ActivityLogController::class, 'clear'])
        ->name('activity.logs.clear');
});
