<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 🚀 Bersihkan query string kosong di awal
        $cleanQuery = array_filter($request->query(), fn($v) => $v !== null && $v !== '');
        if ($request->query() !== $cleanQuery) {
            return redirect()->route('student.index', $cleanQuery);
        }

        $query = Student::query();

        // Ambil filter dari request
        $search   = trim((string) $request->input('search'));
        $juz      = $request->input('juz');
        $penyimak = trim((string) $request->input('penyimak'));
        $tahun    = $request->input('tahun');

        // Filter khusus: jika bukan admin, tampilkan siswa milik guru atau yang belum punya penyimak
        if (Auth::user()->role !== 'admin') {
            $me = Auth::user()->name;
            $query->where(function ($q) use ($me) {
                $q->where('penyimak', $me)
                    ->orWhereNull('penyimak');
            });
        }

        // Filter pencarian nama
        if ($search !== '') {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        // Filter juz
        if ($juz !== null && $juz !== '') {
            $query->where('juz', (int) $juz);
        }

        // Filter tahun ajaran
        if ($tahun !== null && $tahun !== '') {
            $query->where('tahun_ajaran', (int) $tahun);
        }

        // Filter penyimak (khusus admin)
        if ($penyimak !== '' && Auth::user()->role === 'admin') {
            $query->where('penyimak', $penyimak);
        }

        // Ambil daftar tahun ajaran unik
        $tahunList = Student::query()
            ->select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        // Ambil daftar penyimak unik
        $penyimakList = Student::query()
            ->whereNotNull('penyimak')
            ->distinct()
            ->orderBy('penyimak')
            ->pluck('penyimak');

        // Urutkan dan paginasi
        $students = $query->orderBy('juz')
            ->orderBy('nama')
            ->paginate(30)
            ->appends($cleanQuery); // hanya bawa query yg bersih

        return view('students.index', compact(
            'students',
            'juz',
            'search',
            'tahun',
            'tahunList',
            'penyimak',
            'penyimakList'
        ));
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
            'nama'                   => 'required|string|max:255',
            'no_induk'               => 'required|string|max:255|unique:students,no_induk',
            'juz'                    => 'required|integer|min:1|max:30',
            'surat'                  => 'required|array|min:1',
            'surat.*.surat_ke'       => 'required|integer|min:1',
            'surat.*.nama_surat'     => 'required|string|max:100',
            'surat.*.ayat'           => 'required|string|max:100',
            'surat.*.kelancaran'     => 'nullable|integer|min:0|max:33',
            'surat.*.fasohah'        => 'nullable|integer|min:0|max:33',
            'surat.*.tajwid'         => 'nullable|integer|min:0|max:33',
        ], [
            'surat.required'         => 'Silakan pilih Juz terlebih dahulu.',
            'surat.*.surat_ke.required' => 'Data surat tidak lengkap.',
            'no_induk.unique'        => 'No Induk sudah terdaftar.',
        ]);

        $validated['no_induk'] = trim($validated['no_induk']);

        // Teacher otomatis menjadi penyimak
        $penyimak = null;
        if (Auth::user()->role === 'teacher') {
            $penyimak = Auth::user()->name;
        }

        DB::transaction(function () use ($validated, $penyimak) {
            // simpan murid
            $student = Student::create([
                'nama'          => $validated['nama'],
                'no_induk'      => $validated['no_induk'],
                'penyimak'      => $penyimak,
                'juz'           => $validated['juz'],
                'tahun_ajaran'  => now()->year,
            ]);

            // simpan nilai per surat
            foreach ($validated['surat'] as $row) {
                $kelancaran = (int)($row['kelancaran'] ?? 0);
                $fasohah    = (int)($row['fasohah'] ?? 0);
                $tajwid     = (int)($row['tajwid'] ?? 0);

                $total = $kelancaran + $fasohah + $tajwid;
                $bobot = $this->getBobotByJuz($validated['juz']);
                $nilai = max(0, 100 - ($total * $bobot));
                $pred  = $this->getPredikat($nilai);

                $student->surats()->create([
                    'surat_ke'        => (int)$row['surat_ke'],
                    'nama_surat'      => $row['nama_surat'],
                    'ayat'            => $row['ayat'],
                    'kelancaran'      => $kelancaran,
                    'fasohah'         => $fasohah,
                    'tajwid'          => $tajwid,
                    'total_kesalahan' => $total,
                    'nilai'           => $nilai,
                    'predikat'        => $pred,
                ]);
            }
        });

        return redirect()->route('student.index')
            ->with('success', 'Murid dan nilai per surat berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('surats');
        $juzData = $this->getJuzDataInternal();

        if (!isset($juzData[$student->juz])) {
            abort(404, "Data surat untuk Juz {$student->juz} belum tersedia.");
        }

        $surats = collect($juzData[$student->juz])->map(function ($s) use ($student) {
            $nilai = $student->surats->firstWhere('surat_ke', $s['surat_ke']);

            return (object) [
                'id'              => $nilai->id ?? null,
                'surat_ke'        => (int)$s['surat_ke'],
                'nama_surat'      => $s['nama_surat'],
                'ayat'            => (int)$s['ayat'],
                'kelancaran'      => (int)($nilai->kelancaran ?? 0),
                'fasohah'         => (int)($nilai->fasohah ?? 0),
                'tajwid'          => (int)($nilai->tajwid ?? 0),
                'total_kesalahan' => (int)($nilai->total_kesalahan ?? 0),
                'nilai'           => (float)($nilai->nilai ?? 0),
                'predikat'        => (string)($nilai->predikat ?? ''),
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
            'nama'                   => 'required|string|max:255',
            'no_induk'               => 'required|string|max:255|unique:students,no_induk,' . $student->id,
            'juz'                    => 'required|integer|min:1|max:30',
            'surat'                  => 'required|array|min:1',
            'surat.*.id'             => 'required|integer|exists:student_surats,id',
            'surat.*.kelancaran'     => 'required|integer|min:0|max:33',
            'surat.*.fasohah'        => 'required|integer|min:0|max:33',
            'surat.*.tajwid'         => 'required|integer|min:0|max:33',
        ], [
            'no_induk.unique'        => 'No Induk sudah terdaftar.',
            'juz.max'                => 'Juz tidak boleh lebih dari 30',
            'surat.*.kelancaran.required' => 'Kelancaran untuk setiap surat harus diisi.',
            'surat.*.fasohah.required'    => 'Fasohah untuk setiap surat harus diisi.',
            'surat.*.tajwid.required'     => 'Tajwid untuk setiap surat harus diisi.',
        ]);

        DB::transaction(function () use ($validated, $student) {
            // Aturan penyimak:
            // - teacher: set otomatis ke nama guru yang update
            // - selain itu: pertahankan penyimak lama
            $penyimak = $student->penyimak;
            if (Auth::user()->role === 'teacher') {
                $penyimak = Auth::user()->name;
            }

            $student->update([
                'nama'       => $validated['nama'],
                'no_induk'   => trim($validated['no_induk']),
                'juz'        => (int)$validated['juz'],
                'penyimak'   => $penyimak,
            ]);

            foreach ($validated['surat'] as $suratData) {
                $surat = $student->surats()->where('id', $suratData['id'])->firstOrFail();

                $total = (int)$suratData['kelancaran'] + (int)$suratData['fasohah'] + (int)$suratData['tajwid'];
                $bobot = $this->getBobotByJuz((int)$validated['juz']);
                $nilai = max(0, 100 - ($total * $bobot));
                $pred  = $this->getPredikat($nilai);

                $surat->update([
                    'kelancaran'      => (int)$suratData['kelancaran'],
                    'fasohah'         => (int)$suratData['fasohah'],
                    'tajwid'          => (int)$suratData['tajwid'],
                    'total_kesalahan' => $total,
                    'nilai'           => $nilai,
                    'predikat'        => $pred,
                ]);
            }
        });

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

    /**
     * Import CSV sederhana: kolom [nama,no_induk,juz]
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        // Baca semua baris & hilangkan BOM
        $rows = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$rows) {
            return back()->with('error', 'File kosong atau tidak bisa dibaca.');
        }
        // Hilangkan BOM UTF-8 pada baris pertama jika ada
        $rows[0] = preg_replace('/^\xEF\xBB\xBF/', '', $rows[0]);

        $csvData = array_map('str_getcsv', $rows);

        // Deteksi header
        $hasHeader = false;
        $first = array_map('strtolower', array_map('trim', $csvData[0] ?? []));
        if (in_array('nama', $first) && in_array('no_induk', $first)) {
            $hasHeader = true;
            array_shift($csvData);
        }

        DB::transaction(function () use ($csvData) {
            foreach ($csvData as $row) {
                $nama    = trim($row[0] ?? '');
                $noInduk = trim($row[1] ?? '');
                $juz     = isset($row[2]) && is_numeric($row[2]) ? (int)$row[2] : 0;

                if ($nama === '' || $noInduk === '') {
                    continue;
                }

                // Ambil student lama (jika ada) untuk mempertahankan penyimak
                $oldStudent = Student::where('no_induk', $noInduk)->first();

                // Tentukan penyimak: prioritas lama, jika kosong dan role teacher → pakai guru pengunggah
                $penyimak = $oldStudent->penyimak ?? null;
                if (!$penyimak && Auth::user()->role === 'teacher') {
                    $penyimak = Auth::user()->name;
                }

                // Simpan / update student
                $student = Student::updateOrCreate(
                    ['no_induk' => $noInduk],
                    [
                        'nama'          => $nama,
                        'juz'           => max(0, min(30, (int)$juz)),
                        'penyimak'      => $penyimak,
                        'tahun_ajaran'  => now()->year,
                    ]
                );

                // Tambahkan data surat jika juz valid
                $juzData = $this->getJuzDataInternal();
                $jj = (int)$student->juz;

                if ($jj >= 1 && $jj <= 30 && isset($juzData[$jj])) {
                    foreach ($juzData[$jj] as $s) {
                        $student->surats()->firstOrCreate(
                            ['surat_ke' => (int)$s['surat_ke']],
                            [
                                'nama_surat'       => $s['nama_surat'],
                                'ayat'             => (int)$s['ayat'],
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
        });

        return redirect()->back()->with('success', 'CSV berhasil diimport!');
    }

    /**
     * Update inline nilai surat.
     */
    public function updateInline(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'surat'                  => 'required|array|min:1',
            'surat.*.id'             => 'required|integer|exists:student_surats,id',
            'surat.*.kelancaran'     => 'required|integer|min:0|max:33',
            'surat.*.fasohah'        => 'required|integer|min:0|max:33',
            'surat.*.tajwid'         => 'required|integer|min:0|max:33',
        ]);

        DB::transaction(function () use ($validated, $student) {
            foreach ($validated['surat'] as $row) {
                $surat = $student->surats()->where('id', $row['id'])->first();
                if (!$surat) continue;

                $total = (int)$row['kelancaran'] + (int)$row['fasohah'] + (int)$row['tajwid'];
                $bobot = $this->getBobotByJuz((int)$student->juz);
                $nilai = max(0, 100 - ($total * $bobot));

                $predikat = $this->getPredikat($nilai);

                $surat->update([
                    'kelancaran'      => (int)$row['kelancaran'],
                    'fasohah'         => (int)$row['fasohah'],
                    'tajwid'          => (int)$row['tajwid'],
                    'total_kesalahan' => $total,
                    'nilai'           => $nilai,
                    'predikat'        => $predikat,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Nilai berhasil diperbarui.');
    }

    public function generatePdf($id)
    {
        $student = Student::with('surats')->findOrFail($id);
        $juzData = $this->getJuzDataInternal();

        if (!isset($juzData[$student->juz])) {
            return back()->with('error', "Data untuk Juz {$student->juz} tidak ditemukan di config.");
        }

        // Gabungkan data surat & nilai
        $surats = collect($juzData[$student->juz])->map(function ($s) use ($student) {
            $nilai = $student->surats->firstWhere('surat_ke', $s['surat_ke']);

            return (object) [
                'surat_ke'        => (int) $s['surat_ke'],
                'nama_surat'      => $s['nama_surat'],
                'ayat'            => (string) $s['ayat'],
                'nilai'           => $nilai->nilai ?? 0,
                'predikat'        => (string) ($nilai->predikat ?? '-'),
            ];
        });

        $pdf = Pdf::loadView('students.pdf-siswa', compact('student', 'surats'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("nilai-{$student->nama}.pdf");
    }

    public function rekapTahunanPdf($tahun)
    {
        $students = Student::with('surats')
            ->where('tahun_ajaran', (int)$tahun)
            ->orderBy('penyimak')
            ->orderBy('juz')
            ->orderBy('nama')
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', "Tidak ada data untuk tahun $tahun");
        }

        // Kelompokkan berdasarkan penyimak
        $groupedStudents = $students->groupBy('penyimak');

        $pdf = Pdf::loadView('students.rekap-tahunan-pdf', compact('groupedStudents', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("rekap-siswa-{$tahun}.pdf");
    }

    public function exportSampleCsv(): StreamedResponse
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=sample_students.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = ['nama', 'no_induk', 'juz'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // header
            fputcsv($file, ['User 1', '202501', '1']);
            fputcsv($file, ['User 2', '202502', '2']);
            fputcsv($file, ['User 3', '202503', '3']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
        if ($juz >= 1 && $juz <= 15) return 1.7;
        if ($juz >= 16 && $juz <= 30) return 1.9;
        return 1.7;
    }

    private function getPredikat($nilai)
    {
        if ($nilai >= 96) return 'A+';
        if ($nilai >= 90) return 'A';
        if ($nilai >= 86) return 'B+';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 74.5) return 'C';
        return 'D';
    }
}
