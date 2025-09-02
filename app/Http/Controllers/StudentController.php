<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentSurat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        $search = $request->input('search');
        $juz = $request->input('juz');

        // Filter penyimak: jika bukan admin, tampilkan siswa milik guru atau yang belum punya penyimak
        if (auth()->user()->role !== 'admin') {
            $query->where(function ($q) {
                $q->where('penyimak', auth()->user()->name)
                    ->orWhereNull('penyimak');
            });
        }

        // Pencarian berdasarkan nama
        if (!empty($search)) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        // Filter berdasarkan Juz
        if (!empty($juz)) {
            $query->where('juz', $juz);
        }

        // Urutkan nama ascending
        $query->orderBy('juz', 'asc')->orderBy('nama', 'asc');

        // Pagination 30 per halaman
        $students = $query->paginate(30)->withQueryString();

        return view('students.index', compact('students', 'juz', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $juzData = $this->getJuzDataInternal();
        return view('students.create', compact('juzData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_induk' => 'required|string|max:255|unique:students,no_induk',
            'juz' => 'required|integer|min:1|max:30',
            'surat' => 'required|array|min:1',
            'surat.*.surat_ke'   => 'required|integer',
            'surat.*.nama_surat' => 'required|string',
            'surat.*.ayat'       => 'required|string',
            'surat.*.kelancaran' => 'nullable|integer|min:0',
            'surat.*.fasohah'    => 'nullable|integer|min:0',
            'surat.*.tajwid'     => 'nullable|integer|min:0',
        ], [
            'surat.required' => 'Silakan pilih Juz terlebih dahulu.',
            'surat.*.surat_ke.required' => 'Data surat tidak lengkap.',
            'no_induk.unique' => 'No Induk sudah terdaftar.',
        ]);

        // override penyimak dari user login
        $validated['penyimak'] = auth()->user()->name;
        $validated['no_induk'] = trim($validated['no_induk']);

        // simpan murid
        $student = Student::create([
            'nama' => $validated['nama'],
            'no_induk' => $validated['no_induk'],
            'penyimak' => $validated['penyimak'],
            'juz' => $validated['juz'],
        ]);

        // simpan nilai per surat lewat relasi
        foreach ($validated['surat'] as $row) {
            $kelancaran = (int)($row['kelancaran'] ?? 0);
            $fasohah    = (int)($row['fasohah'] ?? 0);
            $tajwid     = (int)($row['tajwid'] ?? 0);

            $total  = $kelancaran + $fasohah + $tajwid;
            $bobot = $this->getBobotByJuz($validated['juz']);
            $nilai = max(0, 100 - ($total * $bobot));
            $predik = $this->getPredikat($nilai);

            $student->surats()->create([
                'surat_ke'        => $row['surat_ke'],
                'nama_surat'      => $row['nama_surat'],
                'ayat'            => $row['ayat'],
                'kelancaran'      => $kelancaran,
                'fasohah'         => $fasohah,
                'tajwid'          => $tajwid,
                'total_kesalahan' => $total,
                'nilai'           => $nilai,
                'predikat'        => $predik,
            ]);
        }

        return redirect()->route('student.index')
            ->with('success', 'Murid dan nilai per surat berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $juzData = $this->getJuzDataInternal();

        // pastikan data untuk juz ada
        if (!isset($juzData[$student->juz])) {
            abort(404, "Data surat untuk Juz {$student->juz} belum tersedia.");
        }

        $surats = collect($juzData[$student->juz])->map(function ($s) use ($student) {
            $nilai = $student->surats()->where('surat_ke', $s['surat_ke'])->first();

            return (object) [
                'id'              => $nilai->id ?? null,
                'surat_ke'        => $s['surat_ke'],
                'nama_surat'      => $s['nama_surat'],
                'ayat'            => $s['ayat'],
                'kelancaran'      => $nilai->kelancaran ?? 0,
                'fasohah'         => $nilai->fasohah ?? 0,
                'tajwid'          => $nilai->tajwid ?? 0,
                'total_kesalahan' => $nilai->total_kesalahan ?? 0,
                'nilai'           => $nilai->nilai ?? 0,
                'predikat'        => $nilai->predikat ?? '',
            ];
        });

        return view('students.show', compact('student', 'surats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_induk' => 'required|string|max:255|unique:students,no_induk,' . $student->id,
            'juz' => 'required|integer|min:1|max:30',
            'surat' => 'required|array|min:1',
            'surat.*.id' => 'required|integer|exists:student_surats,id',
            'surat.*.kelancaran' => 'required|integer|min:0',
            'surat.*.fasohah' => 'required|integer|min:0',
            'surat.*.tajwid' => 'required|integer|min:0',
        ], [
            'no_induk.unique' => 'No Induk sudah terdaftar.',
            'juz.max' => 'Juz tidak boleh lebih dari 30',
            'surat.*.kelancaran.required' => 'Kelancaran untuk setiap surat harus diisi.',
            'surat.*.fasohah.required' => 'Fasohah untuk setiap surat harus diisi.',
            'surat.*.tajwid.required' => 'Tajwid untuk setiap surat harus diisi.',
        ]);

        // Hanya update penyimak jika role teacher
        if (auth()->user()->role === 'teacher') {
            $validated['penyimak'] = auth()->user()->name;
        }

        // Update data murid
        $student->update([
            'nama' => $validated['nama'],
            'no_induk' => trim($validated['no_induk']),
            'juz' => $validated['juz'],
            'penyimak' => $student->penyimak,
        ]);

        // Update nilai per surat
        foreach ($validated['surat'] as $suratData) {
            $surat = $student->surats()->where('id', $suratData['id'])->firstOrFail();

            $total = $suratData['kelancaran'] + $suratData['fasohah'] + $suratData['tajwid'];
            $bobot = $this->getBobotByJuz($validated['juz']);
            $nilai = max(0, 100 - ($total * $bobot));
            $predikat = $this->getPredikat($nilai);

            $surat->update([
                'kelancaran' => $suratData['kelancaran'],
                'fasohah' => $suratData['fasohah'],
                'tajwid' => $suratData['tajwid'],
                'total_kesalahan' => $total,
                'nilai' => $nilai,
                'predikat' => $predikat,
            ]);
        }

        return redirect()->route('student.index')
            ->with('success', 'Murid dan nilai per surat berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('student.index')->with('success', 'Siswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');

        // Baca CSV dan hapus BOM jika ada
        $csvData = array_map(function ($row) {
            return str_getcsv($row);
        }, file($file->getRealPath()));

        foreach ($csvData as $index => $row) {
            if ($index === 0) continue; // skip header

            $nama = $row[0] ?? null;
            $noInduk = $row[1] ?? null;
            $juz = isset($row[2]) && is_numeric($row[2]) ? (int)$row[2] : 0;

            if (!$nama || !$noInduk) continue;

            // Ambil student lama (jika ada) untuk mempertahankan penyimak
            $oldStudent = Student::where('no_induk', $noInduk)->first();

            // Tentukan penyimak
            $penyimak = $oldStudent->penyimak ?? null;
            if (auth()->user()->role === 'teacher') {
                $penyimak = auth()->user()->name;
            }

            // Simpan atau update student
            $student = Student::updateOrCreate(
                ['no_induk' => $noInduk],
                [
                    'nama'     => $nama,
                    'juz'      => $juz,
                    'penyimak' => $penyimak,
                ]
            );

            // Ambil data surat berdasarkan Juz
            $juzData = $this->getJuzDataInternal();

            // Pastikan Juz ada
            if (isset($juzData[$juz])) {
                foreach ($juzData[$juz] as $surat) {
                    $student->surats()->firstOrCreate(
                        ['surat_ke' => $surat['surat_ke']], // kondisi unik
                        [
                            'nama_surat'       => $surat['nama_surat'],
                            'ayat'             => $surat['ayat'],
                            'kelancaran'       => 0,
                            'fasohah'          => 0,
                            'tajwid'           => 0,
                            'total_kesalahan'  => 0,
                            'nilai'            => 0,
                            'predikat'         => 'Belum Dinilai',
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'CSV berhasil diimport!');
    }

    public function updateInline(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Validasi hanya untuk input manual
        $validated = $request->validate([
            'surat' => 'required|array|min:1',
            'surat.*.id' => 'required|integer|exists:student_surats,id',
            'surat.*.kelancaran' => 'required|integer|min:0|max:33',
            'surat.*.fasohah' => 'required|integer|min:0|max:33',
            'surat.*.tajwid' => 'required|integer|min:0|max:33',
        ]);

        foreach ($validated['surat'] as $suratData) {
            $surat = $student->surats()->where('id', $suratData['id'])->first();
            if (!$surat) continue;

            // Hitung otomatis
            $total = $suratData['kelancaran'] + $suratData['fasohah'] + $suratData['tajwid'];
            $bobot = $this->getBobotByJuz($student->juz);
            $nilai = max(0, 100 - ($total * $bobot));

            if ($nilai >= 96) $predikat = 'A+';
            elseif ($nilai >= 90) $predikat = 'A';
            elseif ($nilai >= 86) $predikat = 'B+';
            elseif ($nilai >= 80) $predikat = 'B';
            elseif ($nilai >= 74.5) $predikat = 'C';
            else $predikat = 'D';

            $surat->update([
                'kelancaran' => $suratData['kelancaran'],
                'fasohah' => $suratData['fasohah'],
                'tajwid' => $suratData['tajwid'],
                'total_kesalahan' => $total,
                'nilai' => $nilai,
                'predikat' => $predikat,
            ]);
        }

        return redirect()->back()->with('success', 'Nilai berhasil diperbarui.');
    }

    public function generatePdf($id)
    {
        $student = Student::with('surats')->findOrFail($id);
        $juzData = $this->getJuzDataInternal();

        // Pastikan data juz tersedia
        if (!isset($juzData[$student->juz])) {
            return back()->with('error', "Data untuk Juz {$student->juz} tidak ditemukan di config.");
        }

        // Ambil daftar surat sesuai juz murid
        $surats = collect($juzData[$student->juz])->map(function ($s) use ($student) {
            $nilai = $student->surats()->where('surat_ke', $s['surat_ke'])->first();

            return (object) [
                'surat_ke'        => $s['surat_ke'],
                'nama_surat'      => $s['nama_surat'],
                'ayat'            => $s['ayat'],
                'kelancaran'      => $nilai->kelancaran ?? 0,
                'fasohah'         => $nilai->fasohah ?? 0,
                'tajwid'          => $nilai->tajwid ?? 0,
                'total_kesalahan' => $nilai->total_kesalahan ?? 0,
                'nilai'           => $nilai->nilai ?? 0,
                'predikat'        => $nilai->predikat ?? '-',
            ];
        });

        // Generate PDF
        $pdf = PDF::loadView('students.pdf', compact('student', 'surats'));

        return $pdf->download("nilai-{$student->nama}.pdf");
    }

    // ==========================
    // Private Helper Functions
    // ==========================

    private function getJuzDataInternal()
    {
        return config('juz.surat');
    }

    private function getBobotByJuz(int $juz): float
    {
        if ($juz >= 1 && $juz <= 15) {
            return 1.7;
        }

        if ($juz >= 16 && $juz <= 30) {
            return 1.9;
        }

        return 1.7;
    }

    private function getPredikat($nilai)
    {
        if ($nilai >= 96) return "A+";
        elseif ($nilai >= 90) return "A";
        elseif ($nilai >= 86) return "B+";
        elseif ($nilai >= 80) return "B";
        elseif ($nilai >= 74.5) return "C";
        else return "D";
    }
}
