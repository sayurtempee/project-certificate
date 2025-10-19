<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'teacher') {
            // Hanya siswa milik teacher
            $studentsCount = $user->students()->count();
            $certificatesCount = $user->students()->has('surats')->count();
        } else {
            // Admin atau role lain: hitung semua
            $studentsCount = Student::count();
            $certificatesCount = Student::has('surats')->count();
        }

        // Data guru tetap sama
        $teachers = User::where('role', 'teacher')
            ->get(['id', 'name', 'email', 'is_online', 'last_seen']);

        $teachersCount = $teachers->count();
        $teacherCount = $teachersCount;

        return view('dashboard', [
            'user' => $user,
            'studentsCount' => $studentsCount,
            'certificatesCount' => $certificatesCount,
            'teacherCount' => $teacherCount,
            'teachersCount' => $teachersCount,
            'teachers' => $teachers,
        ]);
    }
}
