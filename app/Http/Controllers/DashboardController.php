<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudentSurat;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'teacher') {
            $studentsCount = $user->students()->count();

            // Hitung jumlah siswa yang punya surat
            $certificatesCount = Student::where('user_id', $user->id)
                ->whereHas('surats')
                ->count();
        } else {
            $studentsCount = Student::count();
            $certificatesCount = Student::has('surats')->count();
        }

        // --- Data guru (untuk daftar dan hitungan) ---
        $teachers = User::where('role', 'teacher')
            ->select('id', 'name', 'email', 'is_online', 'last_seen')
            ->get();

        $teachersCount = $teachers->count();

        // --- Kirim ke view ---
        return view('dashboard', [
            'user'              => $user,
            'studentsCount'     => $studentsCount,
            'certificatesCount' => $certificatesCount,
            'teachersCount'     => $teachersCount,
            'teachers'          => $teachers,
        ]);
    }
}
