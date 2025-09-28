<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        if (!Auth::check() && Auth::user()->role === 'teacher') {
            abort(403, 'Unauthorized action.');
        }

        $students = Student::all();
        return view('certificates.index', compact('students'));
    }

    public function showCertificate($id)
    {
        if (!(Auth::check() && Auth::user()->role === 'teacher')) {
            abort(403, 'Unauthorized');
        }

        $student = Student::with('surats')->findOrFail($id);
        return view('certificates.show', compact('student'));
    }

    public function downloadCertificate($id)
    {
        if (!(Auth::check() && Auth::user()->role === 'teacher')) {
            abort(403, 'Unauthorized');
        }

        $student = Student::with('surats')->findOrFail($id);

        $pdf = Pdf::loadView('certificates.template', compact('student'))
            ->setPaper([0, 0, 1280, 905], 'landscape');

        return $pdf->download('sertifikat-murid-' . $student->id . '.pdf');
    }
}
