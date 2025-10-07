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
        return view('certificates.show', compact('student'));
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
        $student->tempat_kelulusan = $validateData['tempat_kelulusan']  ?: null;
        $student->save();

        return redirect()->route('certificates.index')
            ->with('success', 'Berhasil Menambahkan Tempat Kelulusan.');
    }

    public function updateNamaKepsek(Request $request, $id__)
    {
        if (!Auth::check() || Auth::user()->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $validateData = $request->validate([
            'nm_kepsek' => 'nullable|string|max:255'
        ]);

        $student = Student::findOrFail($id__);
        $nipMapping = [
            'Neor Imanah, M.Pd' => '1234567890',
            'Euis Rahmawaty, M.Pd' => '7890123456'
        ];

        $errorMessage = null;
        if (isset($nipMapping[$validateData['nm_kepsek']])) {
            $expectedNip = $nipMapping[$validateData['nm_kepsek']];
            if ($student->nip_kepsek && $student->nip_kepsek !== $expectedNip) {
                $errorMessage = "NIP untuk {$validateData['nm_kepsek']} harus {$expectedNip}";
            }

            $student->nip_kepsek = $expectedNip;
        }

        $student->nm_kepsek = $validateData["nm_kepsek"] ?: null;
        $student->save();

        if ($errorMessage) {
            return redirect()->back()->withInput()->with("error", $errorMessage);
        }
        $student->nm_kepsek = $validateData['nm_kepsek'] ?: null;;
        $student->save();

        return redirect()->route('certificates.index')->with('success', 'Berhasil Menambahkan Nama Kepala Sekolah');
    }

    public function updateNipKepsek(Request $request, $nip_id)
    {
        if (!Auth::check() || Auth::user()->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $validateData = $request->validate([
            'nip_kepsek' => 'nullable|string|max:255'
        ]);

        $student = Student::findOrFail($nip_id);
        try {
            $student->nip_kepsek = $validateData['nip_kepsek'] ?: null;
            $student->save();

            return redirect()->route('certificates.index')
                ->with('success', 'Berhasil Menambahkan atau Memperbarui NIP Kepala Sekolah');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
