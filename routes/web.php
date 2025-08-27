<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\UpdateLastSeen;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (satu halaman untuk semua role)
Route::middleware(['auth', UpdateLastSeen::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('teacher', TeacherController::class);
    Route::resource('student', StudentController::class);
    Route::put('student/update-inline/{id}', [StudentController::class, 'updateInline'])->name('student.updateInline');
    Route::post('/students/import', [StudentController::class, 'import'])->name('student.import');
    Route::get('/student/{id}/pdf', [StudentController::class, 'generatePdf'])->name('student.pdf');
});
