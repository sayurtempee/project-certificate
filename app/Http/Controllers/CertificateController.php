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
        $user = Auth::user();

        if (!$user || $user->role !== 'teacher') {
            abort(403, 'Unauthorized action.');
        }

        // Ambil hanya siswa milik teacher yang login
        $students = $user->students()->with('surats')->orderBy('nama')->get();

        return view('certificates.index', compact('students'));
    }

    public function showCertificate($id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        // Pastikan siswa milik teacher
        $student = $user->students()->with('surats')->findOrFail($id);

        return view('certificates.show', compact('student'));
    }

    public function downloadCertificate($id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $student = $user->students()->with('surats')->findOrFail($id);

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
        $user = Auth::user();
        if (!$user || $user->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $request->validate(['tanggal_lulus' => 'nullable|date']);

        $student = $user->students()->findOrFail($id);

        $student->tanggal_lulus = $request->input('tanggal_lulus') ? Carbon::parse($request->input('tanggal_lulus')) : null;
        $student->save();

        return redirect()->route('certificates.index')->with('success', 'Berhasil Menambahkan Tanggal Kelulusan.');
    }

    public function updateTempatKelulusan(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $validateData = $request->validate([
            'tempat_kelulusan' => 'nullable|string|max:255'
        ]);

        $student = $user->students()->findOrFail($id);

        $student->tempat_kelulusan = $validateData['tempat_kelulusan'] ?: null;
        $student->save();

        return redirect()->route('certificates.index')->with('success', 'Berhasil Menambahkan Tempat Kelulusan.');
    }

    public function updateNamaKepsek(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $validateData = $request->validate([
            'nm_kepsek' => 'nullable|string|max:255'
        ]);

        $student = $user->students()->findOrFail($id);

        $nipMapping = [
            'Neor Imanah, M.Pd' => '1234567890',
            'Euis Rahmawaty, M.Pd' => '7890123456'
        ];

        if (isset($nipMapping[$validateData['nm_kepsek']])) {
            $student->nip_kepsek = $nipMapping[$validateData['nm_kepsek']];
        }

        $student->nm_kepsek = $validateData['nm_kepsek'] ?: null;
        $student->save();

        return redirect()->route('certificates.index')->with('success', 'Berhasil Menambahkan Nama Kepala Sekolah');
    }

    public function updateNipKepsek(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $validateData = $request->validate([
            'nip_kepsek' => 'nullable|string|max:255'
        ]);

        $student = $user->students()->findOrFail($id);
        $student->nip_kepsek = $validateData['nip_kepsek'] ?: null;
        $student->save();

        return redirect()->route('certificates.index')->with('success', 'Berhasil Menambahkan atau Memperbarui NIP Kepala Sekolah');
    }
}
