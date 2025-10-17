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
        // Total siswa
        $studentsCount = Student::count();

        // Total sertifikat (hitung siswa yang punya entri surat)
        $certificatesCount = Student::has('surats')->count();

        // Ambil data guru (kirim koleksi ke view untuk status online/offline)
        $teachers = User::where('role', 'teacher')
            ->get(['id', 'name', 'email', 'is_online', 'last_seen']);

        // Jumlah guru (supaya kompatibel dengan nama variabel yang mungkin dipakai di view)
        $teachersCount = $teachers->count();
        // juga sediakan teacherCount jika view lain memakai nama itu
        $teacherCount = $teachersCount;

        return view('dashboard', [
            'user' => Auth::user(),
            'studentsCount' => $studentsCount,
            'certificatesCount' => $certificatesCount,
            'teacherCount' => $teacherCount,
            'teachersCount' => $teachersCount,
            'teachers' => $teachers,
        ]);
    }
}
