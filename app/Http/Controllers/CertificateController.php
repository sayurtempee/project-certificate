<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CertificateController extends Controller
{
    // Helper pembangun surats
    protected function buildSurats(Student $student)
    {
        $juzData = $this->getJuzDataInternal();
        if (!isset($juzData[$student->juz])) {
            return collect();
        }

        return collect($juzData[$student->juz])->map(function ($s) use ($student) {
            $nilai =  $student->surats->firstWhere('surat_ke', $s['surat_ke']);

            return (object) [
                'surat_ke' => (int) $s['surat_ke'],
                'nama_surat' => $s['nama_surat'],
                'ayat' => (string) $s['ayat'],
                'nilai' => isset($nilai->nilai) ? (float) $nilai->nilai : 0.0,
                'predikat' => (string) ($nilai->predikat ?? 'Belum ada predikat'),
            ];
        });
    }

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
        $student = Auth::user()->students()->with('surats')->findOrFail($id);
        $surats = $this->buildSurats($student);

        // Render dua halaman ke file sementara
        $pdfDepanPath = storage_path("app/public/temp-depan.pdf");
        $pdfBelakangPath = storage_path("app/public/temp-belakang.pdf");

        Pdf::loadView('certificates.template', compact('student', 'surats'))
            ->setPaper('a4', 'landscape')
            ->save($pdfDepanPath);

        Pdf::loadView('certificates.template-belakang', compact('student', 'surats'))
            ->setPaper('a4', 'landscape')
            ->save($pdfBelakangPath);

        // Gabungkan dengan FPDI
        $finalPdf = new Fpdi();
        foreach ([$pdfDepanPath, $pdfBelakangPath] as $path) {
            $pageCount = $finalPdf->setSourceFile($path);
            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl = $finalPdf->importPage($i);
                $size = $finalPdf->getTemplateSize($tpl);
                $finalPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $finalPdf->useTemplate($tpl);
            }
        }

        // Output hasil
        $output = storage_path("app/public/sertifikat-{$student->nama}.pdf");
        $finalPdf->Output($output, 'F');

        return response()->download($output)->deleteFileAfterSend(true);
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

    // ==========================
    // Private Helper Functions
    // ==========================
    private function getJuzDataInternal()
    {
        return config('juz.surat');
    }
}
