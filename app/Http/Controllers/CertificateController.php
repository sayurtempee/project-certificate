<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        return view('certificates.show', compact('student'), [
            'student' => $student,
            'certificate' => [
                'tanggal' => now()->translatedFormat('d F Y'),
                'nama_kepala_sekolah' => 'Neor Imanah, M.Pd',
                'nip' => '1234567890'
            ]
        ]);
    }

    public function downloadCertificate($id)
    {
        if (!(Auth::check() && Auth::user()->role === 'teacher')) {
            abort(403, 'Unauthorized');
        }

        $student = Student::with('surats')->findOrFail($id);

        $pdf = Pdf::loadView('certificates.template', compact('student'), [
            'student' => $student,
            'certificate' => [
                'nama_kepala_sekolah' => 'Neor Imanah, M.Pd',
                'nip' => '1234567890'
            ]
        ])->setPaper('a4', 'landscape');

        return $pdf->download('sertifikat-murid-' . $student->nama . '.pdf');
    }

    public function updateTanggalLulus(Request $request, $id)
    {
        // Cek role teacher
        if (!(Auth::check() && Auth::user()->role === 'teacher')) {
            abort(403, 'Unauthorized');
        }

        // Validasi input
        $request->validate([
            'tanggal_lulus' => 'nullable|date',
        ]);

        $student = Student::findOrFail($id);

        // Simpan ke database
        $tanggalLulus = $request->input('tanggal_lulus');
        $student->tanggal_lulus = $tanggalLulus ? Carbon::parse($tanggalLulus) : null;
        $student->save();

        return redirect()->route('certificates.index')->with('success', 'Berhasil Menambahkan Tanggal Kelulusan.');
    }

    public function updateTempatKelulusan(Request $request, $id_)
    {
        if (!Auth::check() || Auth::user()->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $validateData = $request->validate([
            'tempat_kelulusan' => 'nullable|string|max:255'
        ]);

        $student = Student::findOrFail($id_);

        // jika kosong, default Jakarta
        $student->tempat_kelulusan = $validateData['tempat_kelulusan'] ?: 'Jakarta';
        $student->save();

        return redirect()->route('certificates.index')
            ->with('success', 'Berhasil Menambahkan Tempat Kelulusan.');
    }
}
