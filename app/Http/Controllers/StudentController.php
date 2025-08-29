<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentSurat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Hanya tampilkan data sesuai penyimak (guru yang login)
        if (auth()->user()->role !== 'admin') {
            $query->where('penyimak', auth()->user()->name);
        }

        // Pencarian berdasarkan nama
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        // Filter berdasarkan Juz (hanya jika diisi)
        $juz = $request->input('juz');
        if (!empty($juz)) {
            $query->where('juz', $juz);
        }

        // Urutkan nama
        $query->orderBy('nama', 'asc');

        // Pagination
        $students = $query->paginate(30)->withQueryString();

        return view('students.index', compact('students', 'juz', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $juzData = [
            30 => [
                ['surat-ke' => 78, 'nama' => 'An-Naba’', 'ayat' => 40],
                ['surat-ke' => 79, 'nama' => 'An-Nazi’at', 'ayat' => 46],
                ['surat-ke' => 80, 'nama' => '‘Abasa', 'ayat' => 42],
                ['surat-ke' => 81, 'nama' => 'At-Takwir', 'ayat' => 29],
                ['surat-ke' => 82, 'nama' => 'Al-Infitar', 'ayat' => 19],
                ['surat-ke' => 83, 'nama' => 'Al-Mutaffifin', 'ayat' => 36],
                ['surat-ke' => 84, 'nama' => 'Al-Insyiqaq', 'ayat' => 25],
                ['surat-ke' => 85, 'nama' => 'Al-Buruj', 'ayat' => 22],
                ['surat-ke' => 86, 'nama' => 'At-Tariq', 'ayat' => 17],
                ['surat-ke' => 87, 'nama' => 'Al-A’la', 'ayat' => 19],
                ['surat-ke' => 88, 'nama' => 'Al-Ghasyiyah', 'ayat' => 26],
                ['surat-ke' => 89, 'nama' => 'Al-Fajr', 'ayat' => 30],
                ['surat-ke' => 90, 'nama' => 'Al-Balad', 'ayat' => 20],
                ['surat-ke' => 91, 'nama' => 'Asy-Syams', 'ayat' => 15],
                ['surat-ke' => 92, 'nama' => 'Al-Lail', 'ayat' => 21],
                ['surat-ke' => 93, 'nama' => 'Ad-Dhuha', 'ayat' => 11],
                ['surat-ke' => 94, 'nama' => 'Asy-Syarh', 'ayat' => 8],
                ['surat-ke' => 95, 'nama' => 'At-Tin', 'ayat' => 8],
                ['surat-ke' => 96, 'nama' => 'Al-‘Alaq', 'ayat' => 19],
                ['surat-ke' => 97, 'nama' => 'Al-Qadr', 'ayat' => 5],
                ['surat-ke' => 98, 'nama' => 'Al-Bayyinah', 'ayat' => 8],
                ['surat-ke' => 99, 'nama' => 'Az-Zalzalah', 'ayat' => 8],
                ['surat-ke' => 100, 'nama' => 'Al-‘Adiyat', 'ayat' => 11],
                ['surat-ke' => 101, 'nama' => 'Al-Qari’ah', 'ayat' => 11],
                ['surat-ke' => 102, 'nama' => 'At-Takasur', 'ayat' => 8],
                ['surat-ke' => 103, 'nama' => 'Al-‘Asr', 'ayat' => 3],
                ['surat-ke' => 104, 'nama' => 'Al-Humazah', 'ayat' => 9],
                ['surat-ke' => 105, 'nama' => 'Al-Fil', 'ayat' => 5],
                ['surat-ke' => 106, 'nama' => 'Quraisy', 'ayat' => 4],
                ['surat-ke' => 107, 'nama' => 'Al-Ma’un', 'ayat' => 7],
                ['surat-ke' => 108, 'nama' => 'Al-Kausar', 'ayat' => 3],
                ['surat-ke' => 109, 'nama' => 'Al-Kafirun', 'ayat' => 6],
                ['surat-ke' => 110, 'nama' => 'An-Nasr', 'ayat' => 3],
                ['surat-ke' => 111, 'nama' => 'Al-Lahab', 'ayat' => 5],
                ['surat-ke' => 112, 'nama' => 'Al-Ikhlas', 'ayat' => 4],
                ['surat-ke' => 113, 'nama' => 'Al-Falaq', 'ayat' => 5],
                ['surat-ke' => 114, 'nama' => 'An-Nas', 'ayat' => 6],
            ],
            29 => [
                ['surat-ke' => 67, 'nama' => 'Al-Mulk', 'ayat' => 30],
                ['surat-ke' => 68, 'nama' => 'Al-Qalam', 'ayat' => 52],
                ['surat-ke' => 69, 'nama' => 'Al-Haqqah', 'ayat' => 52],
                ['surat-ke' => 70, 'nama' => 'Al-Ma’arij', 'ayat' => 44],
                ['surat-ke' => 71, 'nama' => 'Nuh', 'ayat' => 28],
                ['surat-ke' => 72, 'nama' => 'Al-Jinn', 'ayat' => 28],
                ['surat-ke' => 73, 'nama' => 'Al-Muzzammil', 'ayat' => 20],
                ['surat-ke' => 74, 'nama' => 'Al-Muddassir', 'ayat' => 56],
                ['surat-ke' => 75, 'nama' => 'Al-Qiyamah', 'ayat' => 40],
                ['surat-ke' => 76, 'nama' => 'Al-Insan', 'ayat' => 31],
                ['surat-ke' => 77, 'nama' => 'Al-Mursalat', 'ayat' => 50],
            ],
            28 => [
                ['surat-ke' => 58, 'nama' => 'Al-Mujadilah', 'ayat' => 22],
                ['surat-ke' => 59, 'nama' => 'Al-Hasyr', 'ayat' => 24],
                ['surat-ke' => 60, 'nama' => 'Al-Mumtahanah', 'ayat' => 13],
                ['surat-ke' => 61, 'nama' => 'As-Saff', 'ayat' => 14],
                ['surat-ke' => 62, 'nama' => 'Al-Jumu’ah', 'ayat' => 11],
                ['surat-ke' => 63, 'nama' => 'Al-Munafiqun', 'ayat' => 11],
                ['surat-ke' => 64, 'nama' => 'At-Taghabun', 'ayat' => 18],
                ['surat-ke' => 65, 'nama' => 'At-Talaq', 'ayat' => 12],
                ['surat-ke' => 66, 'nama' => 'At-Tahrim', 'ayat' => 12],
            ],
            27 => [
                ['surat-ke' => 51, 'nama' => 'Adz-Dzariyat', 'ayat' => 60],
                ['surat-ke' => 52, 'nama' => 'At-Tur', 'ayat' => 49],
                ['surat-ke' => 53, 'nama' => 'An-Najm', 'ayat' => 62],
                ['surat-ke' => 54, 'nama' => 'Al-Qamar', 'ayat' => 55],
                ['surat-ke' => 55, 'nama' => 'Ar-Rahman', 'ayat' => 78],
                ['surat-ke' => 56, 'nama' => 'Al-Waqi’ah', 'ayat' => 96],
                ['surat-ke' => 57, 'nama' => 'Al-Hadid', 'ayat' => 29],
            ],
            26 => [
                ['surat-ke' => 46, 'nama' => 'Al-Ahqaf', 'ayat' => 35],
                ['surat-ke' => 47, 'nama' => 'Muhammad', 'ayat' => 38],
                ['surat-ke' => 48, 'nama' => 'Al-Fath', 'ayat' => 29],
                ['surat-ke' => 49, 'nama' => 'Al-Hujurat', 'ayat' => 18],
                ['surat-ke' => 50, 'nama' => 'Qaf', 'ayat' => 45],
            ],
            25 => [
                ['surat-ke' => 41, 'nama' => 'Fussilat', 'ayat' => 54],
                ['surat-ke' => 42, 'nama' => 'Asy-Syura', 'ayat' => 53],
                ['surat-ke' => 43, 'nama' => 'Az-Zukhruf', 'ayat' => 89],
                ['surat-ke' => 44, 'nama' => 'Ad-Dukhan', 'ayat' => 59],
                ['surat-ke' => 45, 'nama' => 'Al-Jasiyah', 'ayat' => 37],
            ],
            24 => [
                ['surat-ke' => 39, 'nama' => 'Az-Zumar', 'ayat' => 75],
                ['surat-ke' => 40, 'nama' => 'Ghafir', 'ayat' => 85],
            ],
            23 => [
                ['surat-ke' => 36, 'nama' => 'Yasin', 'ayat' => 83],
                ['surat-ke' => 37, 'nama' => 'As-Saffat', 'ayat' => 182],
                ['surat-ke' => 38, 'nama' => 'Sad', 'ayat' => 88],
            ],
            22 => [
                ['surat-ke' => 33, 'nama' => 'Al-Ahzab', 'ayat' => 73],
                ['surat-ke' => 34, 'nama' => 'Saba’', 'ayat' => 54],
                ['surat-ke' => 35, 'nama' => 'Fatir', 'ayat' => 45],
            ],
            21 => [
                ['surat-ke' => 29, 'nama' => 'Al-‘Ankabut', 'ayat' => 69],
                ['surat-ke' => 30, 'nama' => 'Ar-Rum', 'ayat' => 60],
                ['surat-ke' => 31, 'nama' => 'Luqman', 'ayat' => 34],
                ['surat-ke' => 32, 'nama' => 'As-Sajdah', 'ayat' => 30],
            ],
            20 => [
                ['surat-ke' => 27, 'nama' => 'An-Naml', 'ayat' => 93],
                ['surat-ke' => 28, 'nama' => 'Al-Qasas', 'ayat' => 88],
                ['surat-ke' => 29, 'nama' => 'Al-‘Ankabut', 'ayat' => 45],
            ],
            19 => [
                ['surat-ke' => 25, 'nama' => 'Al-Furqan', 'ayat' => 77],
                ['surat-ke' => 26, 'nama' => 'Asy-Syu‘ara’', 'ayat' => 227],
                ['surat-ke' => 27, 'nama' => 'An-Naml', 'ayat' => 55],
            ],
            18 => [
                ['surat-ke' => 23, 'nama' => 'Al-Mu’minun', 'ayat' => 118],
                ['surat-ke' => 24, 'nama' => 'An-Nur', 'ayat' => 64],
                ['surat-ke' => 25, 'nama' => 'Al-Furqan', 'ayat' => 20],
            ],
            17 => [
                ['surat-ke' => 21, 'nama' => 'Al-Anbiya’', 'ayat' => 112],
                ['surat-ke' => 22, 'nama' => 'Al-Hajj', 'ayat' => 78],
            ],
            16 => [
                ['surat-ke' => 18, 'nama' => 'Al-Kahf', 'ayat' => 110],
                ['surat-ke' => 19, 'nama' => 'Maryam', 'ayat' => 98],
                ['surat-ke' => 20, 'nama' => 'Ta-Ha', 'ayat' => 135],
            ],
            15 => [
                ['surat-ke' => 17, 'nama' => 'Al-Isra’', 'ayat' => 111],
                ['surat-ke' => 18, 'nama' => 'Al-Kahf', 'ayat' => 74],
            ],
            14 => [
                ['surat-ke' => 15, 'nama' => 'Al-Hijr', 'ayat' => 99],
                ['surat-ke' => 16, 'nama' => 'An-Nahl', 'ayat' => 128],
            ],
            13 => [
                ['surat-ke' => 12, 'nama' => 'Yusuf', 'ayat' => 111],
                ['surat-ke' => 13, 'nama' => 'Ar-Ra’d', 'ayat' => 43],
                ['surat-ke' => 14, 'nama' => 'Ibrahim', 'ayat' => 52],
            ],
            12 => [
                ['surat-ke' => 11, 'nama' => 'Hud', 'ayat' => 123],
                ['surat-ke' => 12, 'nama' => 'Yusuf', 'ayat' => 52],
            ],
            11 => [
                ['surat-ke' => 9, 'nama' => 'At-Taubah', 'ayat' => 129],
                ['surat-ke' => 10, 'nama' => 'Yunus', 'ayat' => 109],
                ['surat-ke' => 11, 'nama' => 'Hud', 'ayat' => 5],
            ],
            10 => [
                ['surat-ke' => 20, 'nama' => 'Taha', 'ayat' => 135],
                ['surat-ke' => 21, 'nama' => 'Al-Anbiya', 'ayat' => 112],
                ['surat-ke' => 22, 'nama' => 'Al-Hajj', 'ayat' => 78],
            ],
            9 => [
                ['surat-ke' => 18, 'nama' => 'Al-Kahfi', 'ayat' => 110],
                ['surat-ke' => 19, 'nama' => 'Maryam', 'ayat' => 98],
            ],
            8 => [
                ['surat-ke' => 13, 'nama' => 'Ar-Ra’d', 'ayat' => 43],
                ['surat-ke' => 14, 'nama' => 'Ibrahim', 'ayat' => 52],
                ['surat-ke' => 15, 'nama' => 'Al-Hijr', 'ayat' => 99],
                ['surat-ke' => 16, 'nama' => 'An-Nahl', 'ayat' => 128],
            ],
            7 => [
                ['surat-ke' => 11, 'nama' => 'Hud', 'ayat' => 123],
                ['surat-ke' => 12, 'nama' => 'Yusuf', 'ayat' => 111],
            ],
            6 => [
                ['surat-ke' => 7, 'nama' => 'Al-A’raf', 'ayat' => 206],
                ['surat-ke' => 8, 'nama' => 'Al-Anfal', 'ayat' => 75],
                ['surat-ke' => 9, 'nama' => 'At-Taubah', 'ayat' => 129],
            ],
            5 => [
                ['surat-ke' => 4, 'nama' => 'An-Nisa', 'ayat' => 176],
                ['surat-ke' => 5, 'nama' => 'Al-Maidah', 'ayat' => 120],
            ],
            4 => [
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 141],
            ],
            3 => [
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 253],
            ],
            2 => [
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 142],
            ],
            1 => [
                ['surat-ke' => 1, 'nama' => 'Al-Fatihah', 'ayat' => 7],
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 141],
            ],
        ];
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
            'surat.*.surat_ke' => 'required|integer',
            'surat.*.nama'     => 'required|string',
            'surat.*.ayat'     => 'required|integer|min:1',
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
        $validated['no_induk'] = trim($validated['no_induk']); // hilangkan spasi

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
            $nilai  = max(0, 100 - $total);
            $predik = $this->getPredikat($nilai);

            $student->surats()->create([
                'surat_ke'        => $row['surat_ke'],
                'nama_surat'      => $row['nama'],
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
        $student->load('surats'); // eager load
        return view('students.show', compact('student'));
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
            $student->penyimak = auth()->user()->name;
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
            $nilai = max(0, 100 - $total);
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
        $csvData = array_map('str_getcsv', file($file->getRealPath()));

        foreach ($csvData as $index => $row) {
            if ($index === 0) continue; // skip header

            // Ambil student berdasarkan no_induk
            $student = Student::where('no_induk', $row[0])->first();
            if (!$student) continue;

            // Pakai nama dari database (bukan dari CSV, biar konsisten)
            $noInduk    = $student->no_induk;
            $nama       = $student->nama;
            $juz        = (int) $row[2];
            $kelancaran = (int) $row[3];
            $fasohah    = (int) $row[4];
            $tajwid     = (int) $row[5];

            // Simpan data surah (kalau memang relasinya ada)
            $student->surats()->create([
                'nama_student'   => $nama,
                'surat_ke'       => 0,   // kalau ada mapping surat, isi sesuai kebutuhan
                'nama_surat'     => '',
                'ayat'           => 0,
                'kelancaran'     => $kelancaran,
                'fasohah'        => $fasohah,
                'tajwid'         => $tajwid,
                'total_kesalahan' => ($kelancaran + $fasohah + $tajwid), // contoh hitung
                'nilai'          => 0,
                'predikat'       => 'D',
            ]);
        }

        return redirect()->back()->with('success', 'CSV berhasil diimport!');
    }

    public function updateInline(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'surat' => 'required|array|min:1',
            'surat.*.id' => 'required|integer|exists:student_surats,id',
            'surat.*.kelancaran' => 'required|integer|min:0|max:33',
            'surat.*.fasohah' => 'required|integer|min:0|max:33',
            'surat.*.tajwid' => 'required|integer|min:0|max:33',
        ], [
            'surat.*.kelancaran.required' => 'Kelancaran harus diisi.',
            'surat.*.fasohah.required' => 'Fasohah harus diisi.',
            'surat.*.tajwid.required' => 'Tajwid harus diisi.',
        ]);

        // Loop tiap surat untuk update
        foreach ($validated['surat'] as $suratData) {
            $surat = $student->surats()->where('id', $suratData['id'])->firstOrFail();

            $total = $suratData['kelancaran'] + $suratData['fasohah'] + $suratData['tajwid'];
            $nilai = max(0, 100 - $total);

            // Tentukan predikat
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
        $juzData = [
            30 => [
                ['surat-ke' => 78, 'nama' => 'An-Naba’', 'ayat' => 40],
                ['surat-ke' => 79, 'nama' => 'An-Nazi’at', 'ayat' => 46],
                ['surat-ke' => 80, 'nama' => '‘Abasa', 'ayat' => 42],
                ['surat-ke' => 81, 'nama' => 'At-Takwir', 'ayat' => 29],
                ['surat-ke' => 82, 'nama' => 'Al-Infitar', 'ayat' => 19],
                ['surat-ke' => 83, 'nama' => 'Al-Mutaffifin', 'ayat' => 36],
                ['surat-ke' => 84, 'nama' => 'Al-Insyiqaq', 'ayat' => 25],
                ['surat-ke' => 85, 'nama' => 'Al-Buruj', 'ayat' => 22],
                ['surat-ke' => 86, 'nama' => 'At-Tariq', 'ayat' => 17],
                ['surat-ke' => 87, 'nama' => 'Al-A’la', 'ayat' => 19],
                ['surat-ke' => 88, 'nama' => 'Al-Ghasyiyah', 'ayat' => 26],
                ['surat-ke' => 89, 'nama' => 'Al-Fajr', 'ayat' => 30],
                ['surat-ke' => 90, 'nama' => 'Al-Balad', 'ayat' => 20],
                ['surat-ke' => 91, 'nama' => 'Asy-Syams', 'ayat' => 15],
                ['surat-ke' => 92, 'nama' => 'Al-Lail', 'ayat' => 21],
                ['surat-ke' => 93, 'nama' => 'Ad-Dhuha', 'ayat' => 11],
                ['surat-ke' => 94, 'nama' => 'Asy-Syarh', 'ayat' => 8],
                ['surat-ke' => 95, 'nama' => 'At-Tin', 'ayat' => 8],
                ['surat-ke' => 96, 'nama' => 'Al-‘Alaq', 'ayat' => 19],
                ['surat-ke' => 97, 'nama' => 'Al-Qadr', 'ayat' => 5],
                ['surat-ke' => 98, 'nama' => 'Al-Bayyinah', 'ayat' => 8],
                ['surat-ke' => 99, 'nama' => 'Az-Zalzalah', 'ayat' => 8],
                ['surat-ke' => 100, 'nama' => 'Al-‘Adiyat', 'ayat' => 11],
                ['surat-ke' => 101, 'nama' => 'Al-Qari’ah', 'ayat' => 11],
                ['surat-ke' => 102, 'nama' => 'At-Takasur', 'ayat' => 8],
                ['surat-ke' => 103, 'nama' => 'Al-‘Asr', 'ayat' => 3],
                ['surat-ke' => 104, 'nama' => 'Al-Humazah', 'ayat' => 9],
                ['surat-ke' => 105, 'nama' => 'Al-Fil', 'ayat' => 5],
                ['surat-ke' => 106, 'nama' => 'Quraisy', 'ayat' => 4],
                ['surat-ke' => 107, 'nama' => 'Al-Ma’un', 'ayat' => 7],
                ['surat-ke' => 108, 'nama' => 'Al-Kausar', 'ayat' => 3],
                ['surat-ke' => 109, 'nama' => 'Al-Kafirun', 'ayat' => 6],
                ['surat-ke' => 110, 'nama' => 'An-Nasr', 'ayat' => 3],
                ['surat-ke' => 111, 'nama' => 'Al-Lahab', 'ayat' => 5],
                ['surat-ke' => 112, 'nama' => 'Al-Ikhlas', 'ayat' => 4],
                ['surat-ke' => 113, 'nama' => 'Al-Falaq', 'ayat' => 5],
                ['surat-ke' => 114, 'nama' => 'An-Nas', 'ayat' => 6],
            ],
            29 => [
                ['surat-ke' => 67, 'nama' => 'Al-Mulk', 'ayat' => 30],
                ['surat-ke' => 68, 'nama' => 'Al-Qalam', 'ayat' => 52],
                ['surat-ke' => 69, 'nama' => 'Al-Haqqah', 'ayat' => 52],
                ['surat-ke' => 70, 'nama' => 'Al-Ma’arij', 'ayat' => 44],
                ['surat-ke' => 71, 'nama' => 'Nuh', 'ayat' => 28],
                ['surat-ke' => 72, 'nama' => 'Al-Jinn', 'ayat' => 28],
                ['surat-ke' => 73, 'nama' => 'Al-Muzzammil', 'ayat' => 20],
                ['surat-ke' => 74, 'nama' => 'Al-Muddassir', 'ayat' => 56],
                ['surat-ke' => 75, 'nama' => 'Al-Qiyamah', 'ayat' => 40],
                ['surat-ke' => 76, 'nama' => 'Al-Insan', 'ayat' => 31],
                ['surat-ke' => 77, 'nama' => 'Al-Mursalat', 'ayat' => 50],
            ],
            28 => [
                ['surat-ke' => 58, 'nama' => 'Al-Mujadilah', 'ayat' => 22],
                ['surat-ke' => 59, 'nama' => 'Al-Hasyr', 'ayat' => 24],
                ['surat-ke' => 60, 'nama' => 'Al-Mumtahanah', 'ayat' => 13],
                ['surat-ke' => 61, 'nama' => 'As-Saff', 'ayat' => 14],
                ['surat-ke' => 62, 'nama' => 'Al-Jumu’ah', 'ayat' => 11],
                ['surat-ke' => 63, 'nama' => 'Al-Munafiqun', 'ayat' => 11],
                ['surat-ke' => 64, 'nama' => 'At-Taghabun', 'ayat' => 18],
                ['surat-ke' => 65, 'nama' => 'At-Talaq', 'ayat' => 12],
                ['surat-ke' => 66, 'nama' => 'At-Tahrim', 'ayat' => 12],
            ],
            27 => [
                ['surat-ke' => 51, 'nama' => 'Adz-Dzariyat', 'ayat' => 60],
                ['surat-ke' => 52, 'nama' => 'At-Tur', 'ayat' => 49],
                ['surat-ke' => 53, 'nama' => 'An-Najm', 'ayat' => 62],
                ['surat-ke' => 54, 'nama' => 'Al-Qamar', 'ayat' => 55],
                ['surat-ke' => 55, 'nama' => 'Ar-Rahman', 'ayat' => 78],
                ['surat-ke' => 56, 'nama' => 'Al-Waqi’ah', 'ayat' => 96],
                ['surat-ke' => 57, 'nama' => 'Al-Hadid', 'ayat' => 29],
            ],
            26 => [
                ['surat-ke' => 46, 'nama' => 'Al-Ahqaf', 'ayat' => 35],
                ['surat-ke' => 47, 'nama' => 'Muhammad', 'ayat' => 38],
                ['surat-ke' => 48, 'nama' => 'Al-Fath', 'ayat' => 29],
                ['surat-ke' => 49, 'nama' => 'Al-Hujurat', 'ayat' => 18],
                ['surat-ke' => 50, 'nama' => 'Qaf', 'ayat' => 45],
            ],
            25 => [
                ['surat-ke' => 41, 'nama' => 'Fussilat', 'ayat' => 54],
                ['surat-ke' => 42, 'nama' => 'Asy-Syura', 'ayat' => 53],
                ['surat-ke' => 43, 'nama' => 'Az-Zukhruf', 'ayat' => 89],
                ['surat-ke' => 44, 'nama' => 'Ad-Dukhan', 'ayat' => 59],
                ['surat-ke' => 45, 'nama' => 'Al-Jasiyah', 'ayat' => 37],
            ],
            24 => [
                ['surat-ke' => 39, 'nama' => 'Az-Zumar', 'ayat' => 75],
                ['surat-ke' => 40, 'nama' => 'Ghafir', 'ayat' => 85],
            ],
            23 => [
                ['surat-ke' => 36, 'nama' => 'Yasin', 'ayat' => 83],
                ['surat-ke' => 37, 'nama' => 'As-Saffat', 'ayat' => 182],
                ['surat-ke' => 38, 'nama' => 'Sad', 'ayat' => 88],
            ],
            22 => [
                ['surat-ke' => 33, 'nama' => 'Al-Ahzab', 'ayat' => 73],
                ['surat-ke' => 34, 'nama' => 'Saba’', 'ayat' => 54],
                ['surat-ke' => 35, 'nama' => 'Fatir', 'ayat' => 45],
            ],
            21 => [
                ['surat-ke' => 29, 'nama' => 'Al-‘Ankabut', 'ayat' => 69],
                ['surat-ke' => 30, 'nama' => 'Ar-Rum', 'ayat' => 60],
                ['surat-ke' => 31, 'nama' => 'Luqman', 'ayat' => 34],
                ['surat-ke' => 32, 'nama' => 'As-Sajdah', 'ayat' => 30],
            ],
            20 => [
                ['surat-ke' => 27, 'nama' => 'An-Naml', 'ayat' => 93],
                ['surat-ke' => 28, 'nama' => 'Al-Qasas', 'ayat' => 88],
                ['surat-ke' => 29, 'nama' => 'Al-‘Ankabut', 'ayat' => 45],
            ],
            19 => [
                ['surat-ke' => 25, 'nama' => 'Al-Furqan', 'ayat' => 77],
                ['surat-ke' => 26, 'nama' => 'Asy-Syu‘ara’', 'ayat' => 227],
                ['surat-ke' => 27, 'nama' => 'An-Naml', 'ayat' => 55],
            ],
            18 => [
                ['surat-ke' => 23, 'nama' => 'Al-Mu’minun', 'ayat' => 118],
                ['surat-ke' => 24, 'nama' => 'An-Nur', 'ayat' => 64],
                ['surat-ke' => 25, 'nama' => 'Al-Furqan', 'ayat' => 20],
            ],
            17 => [
                ['surat-ke' => 21, 'nama' => 'Al-Anbiya’', 'ayat' => 112],
                ['surat-ke' => 22, 'nama' => 'Al-Hajj', 'ayat' => 78],
            ],
            16 => [
                ['surat-ke' => 18, 'nama' => 'Al-Kahf', 'ayat' => 110],
                ['surat-ke' => 19, 'nama' => 'Maryam', 'ayat' => 98],
                ['surat-ke' => 20, 'nama' => 'Ta-Ha', 'ayat' => 135],
            ],
            15 => [
                ['surat-ke' => 17, 'nama' => 'Al-Isra’', 'ayat' => 111],
                ['surat-ke' => 18, 'nama' => 'Al-Kahf', 'ayat' => 74],
            ],
            14 => [
                ['surat-ke' => 15, 'nama' => 'Al-Hijr', 'ayat' => 99],
                ['surat-ke' => 16, 'nama' => 'An-Nahl', 'ayat' => 128],
            ],
            13 => [
                ['surat-ke' => 12, 'nama' => 'Yusuf', 'ayat' => 111],
                ['surat-ke' => 13, 'nama' => 'Ar-Ra’d', 'ayat' => 43],
                ['surat-ke' => 14, 'nama' => 'Ibrahim', 'ayat' => 52],
            ],
            12 => [
                ['surat-ke' => 11, 'nama' => 'Hud', 'ayat' => 123],
                ['surat-ke' => 12, 'nama' => 'Yusuf', 'ayat' => 52],
            ],
            11 => [
                ['surat-ke' => 9, 'nama' => 'At-Taubah', 'ayat' => 129],
                ['surat-ke' => 10, 'nama' => 'Yunus', 'ayat' => 109],
                ['surat-ke' => 11, 'nama' => 'Hud', 'ayat' => 5],
            ],
            10 => [
                ['surat-ke' => 20, 'nama' => 'Taha', 'ayat' => 135],
                ['surat-ke' => 21, 'nama' => 'Al-Anbiya', 'ayat' => 112],
                ['surat-ke' => 22, 'nama' => 'Al-Hajj', 'ayat' => 78],
            ],
            9 => [
                ['surat-ke' => 18, 'nama' => 'Al-Kahfi', 'ayat' => 110],
                ['surat-ke' => 19, 'nama' => 'Maryam', 'ayat' => 98],
            ],
            8 => [
                ['surat-ke' => 13, 'nama' => 'Ar-Ra’d', 'ayat' => 43],
                ['surat-ke' => 14, 'nama' => 'Ibrahim', 'ayat' => 52],
                ['surat-ke' => 15, 'nama' => 'Al-Hijr', 'ayat' => 99],
                ['surat-ke' => 16, 'nama' => 'An-Nahl', 'ayat' => 128],
            ],
            7 => [
                ['surat-ke' => 11, 'nama' => 'Hud', 'ayat' => 123],
                ['surat-ke' => 12, 'nama' => 'Yusuf', 'ayat' => 111],
            ],
            6 => [
                ['surat-ke' => 7, 'nama' => 'Al-A’raf', 'ayat' => 206],
                ['surat-ke' => 8, 'nama' => 'Al-Anfal', 'ayat' => 75],
                ['surat-ke' => 9, 'nama' => 'At-Taubah', 'ayat' => 129],
            ],
            5 => [
                ['surat-ke' => 4, 'nama' => 'An-Nisa', 'ayat' => 176],
                ['surat-ke' => 5, 'nama' => 'Al-Maidah', 'ayat' => 120],
            ],
            4 => [
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 141],
            ],
            3 => [
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 253],
            ],
            2 => [
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 142],
            ],
            1 => [
                ['surat-ke' => 1, 'nama' => 'Al-Fatihah', 'ayat' => 7],
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 141],
            ],
        ];

        $student = Student::findOrFail($id);
        $pdf = PDF::loadView('students.pdf', compact('student', 'juzData'));
        return $pdf->download('student-' . $student->nama . '.pdf');
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

    private function getJuzData($juz = null)
    {
        $juzData = [
            30 => [
                ['surat-ke' => 78, 'nama' => 'An-Naba’', 'ayat' => 40],
                ['surat-ke' => 79, 'nama' => 'An-Nazi’at', 'ayat' => 46],
                ['surat-ke' => 80, 'nama' => '‘Abasa', 'ayat' => 42],
                ['surat-ke' => 81, 'nama' => 'At-Takwir', 'ayat' => 29],
                ['surat-ke' => 82, 'nama' => 'Al-Infitar', 'ayat' => 19],
                ['surat-ke' => 83, 'nama' => 'Al-Mutaffifin', 'ayat' => 36],
                ['surat-ke' => 84, 'nama' => 'Al-Insyiqaq', 'ayat' => 25],
                ['surat-ke' => 85, 'nama' => 'Al-Buruj', 'ayat' => 22],
                ['surat-ke' => 86, 'nama' => 'At-Tariq', 'ayat' => 17],
                ['surat-ke' => 87, 'nama' => 'Al-A’la', 'ayat' => 19],
                ['surat-ke' => 88, 'nama' => 'Al-Ghasyiyah', 'ayat' => 26],
                ['surat-ke' => 89, 'nama' => 'Al-Fajr', 'ayat' => 30],
                ['surat-ke' => 90, 'nama' => 'Al-Balad', 'ayat' => 20],
                ['surat-ke' => 91, 'nama' => 'Asy-Syams', 'ayat' => 15],
                ['surat-ke' => 92, 'nama' => 'Al-Lail', 'ayat' => 21],
                ['surat-ke' => 93, 'nama' => 'Ad-Dhuha', 'ayat' => 11],
                ['surat-ke' => 94, 'nama' => 'Asy-Syarh', 'ayat' => 8],
                ['surat-ke' => 95, 'nama' => 'At-Tin', 'ayat' => 8],
                ['surat-ke' => 96, 'nama' => 'Al-‘Alaq', 'ayat' => 19],
                ['surat-ke' => 97, 'nama' => 'Al-Qadr', 'ayat' => 5],
                ['surat-ke' => 98, 'nama' => 'Al-Bayyinah', 'ayat' => 8],
                ['surat-ke' => 99, 'nama' => 'Az-Zalzalah', 'ayat' => 8],
                ['surat-ke' => 100, 'nama' => 'Al-‘Adiyat', 'ayat' => 11],
                ['surat-ke' => 101, 'nama' => 'Al-Qari’ah', 'ayat' => 11],
                ['surat-ke' => 102, 'nama' => 'At-Takasur', 'ayat' => 8],
                ['surat-ke' => 103, 'nama' => 'Al-‘Asr', 'ayat' => 3],
                ['surat-ke' => 104, 'nama' => 'Al-Humazah', 'ayat' => 9],
                ['surat-ke' => 105, 'nama' => 'Al-Fil', 'ayat' => 5],
                ['surat-ke' => 106, 'nama' => 'Quraisy', 'ayat' => 4],
                ['surat-ke' => 107, 'nama' => 'Al-Ma’un', 'ayat' => 7],
                ['surat-ke' => 108, 'nama' => 'Al-Kausar', 'ayat' => 3],
                ['surat-ke' => 109, 'nama' => 'Al-Kafirun', 'ayat' => 6],
                ['surat-ke' => 110, 'nama' => 'An-Nasr', 'ayat' => 3],
                ['surat-ke' => 111, 'nama' => 'Al-Lahab', 'ayat' => 5],
                ['surat-ke' => 112, 'nama' => 'Al-Ikhlas', 'ayat' => 4],
                ['surat-ke' => 113, 'nama' => 'Al-Falaq', 'ayat' => 5],
                ['surat-ke' => 114, 'nama' => 'An-Nas', 'ayat' => 6],
            ],
            29 => [
                ['surat-ke' => 67, 'nama' => 'Al-Mulk', 'ayat' => 30],
                ['surat-ke' => 68, 'nama' => 'Al-Qalam', 'ayat' => 52],
                ['surat-ke' => 69, 'nama' => 'Al-Haqqah', 'ayat' => 52],
                ['surat-ke' => 70, 'nama' => 'Al-Ma’arij', 'ayat' => 44],
                ['surat-ke' => 71, 'nama' => 'Nuh', 'ayat' => 28],
                ['surat-ke' => 72, 'nama' => 'Al-Jinn', 'ayat' => 28],
                ['surat-ke' => 73, 'nama' => 'Al-Muzzammil', 'ayat' => 20],
                ['surat-ke' => 74, 'nama' => 'Al-Muddassir', 'ayat' => 56],
                ['surat-ke' => 75, 'nama' => 'Al-Qiyamah', 'ayat' => 40],
                ['surat-ke' => 76, 'nama' => 'Al-Insan', 'ayat' => 31],
                ['surat-ke' => 77, 'nama' => 'Al-Mursalat', 'ayat' => 50],
            ],
            28 => [
                ['surat-ke' => 58, 'nama' => 'Al-Mujadilah', 'ayat' => 22],
                ['surat-ke' => 59, 'nama' => 'Al-Hasyr', 'ayat' => 24],
                ['surat-ke' => 60, 'nama' => 'Al-Mumtahanah', 'ayat' => 13],
                ['surat-ke' => 61, 'nama' => 'As-Saff', 'ayat' => 14],
                ['surat-ke' => 62, 'nama' => 'Al-Jumu’ah', 'ayat' => 11],
                ['surat-ke' => 63, 'nama' => 'Al-Munafiqun', 'ayat' => 11],
                ['surat-ke' => 64, 'nama' => 'At-Taghabun', 'ayat' => 18],
                ['surat-ke' => 65, 'nama' => 'At-Talaq', 'ayat' => 12],
                ['surat-ke' => 66, 'nama' => 'At-Tahrim', 'ayat' => 12],
            ],
            27 => [
                ['surat-ke' => 51, 'nama' => 'Adz-Dzariyat', 'ayat' => 60],
                ['surat-ke' => 52, 'nama' => 'At-Tur', 'ayat' => 49],
                ['surat-ke' => 53, 'nama' => 'An-Najm', 'ayat' => 62],
                ['surat-ke' => 54, 'nama' => 'Al-Qamar', 'ayat' => 55],
                ['surat-ke' => 55, 'nama' => 'Ar-Rahman', 'ayat' => 78],
                ['surat-ke' => 56, 'nama' => 'Al-Waqi’ah', 'ayat' => 96],
                ['surat-ke' => 57, 'nama' => 'Al-Hadid', 'ayat' => 29],
            ],
            26 => [
                ['surat-ke' => 46, 'nama' => 'Al-Ahqaf', 'ayat' => 35],
                ['surat-ke' => 47, 'nama' => 'Muhammad', 'ayat' => 38],
                ['surat-ke' => 48, 'nama' => 'Al-Fath', 'ayat' => 29],
                ['surat-ke' => 49, 'nama' => 'Al-Hujurat', 'ayat' => 18],
                ['surat-ke' => 50, 'nama' => 'Qaf', 'ayat' => 45],
            ],
            25 => [
                ['surat-ke' => 41, 'nama' => 'Fussilat', 'ayat' => 54],
                ['surat-ke' => 42, 'nama' => 'Asy-Syura', 'ayat' => 53],
                ['surat-ke' => 43, 'nama' => 'Az-Zukhruf', 'ayat' => 89],
                ['surat-ke' => 44, 'nama' => 'Ad-Dukhan', 'ayat' => 59],
                ['surat-ke' => 45, 'nama' => 'Al-Jasiyah', 'ayat' => 37],
            ],
            24 => [
                ['surat-ke' => 39, 'nama' => 'Az-Zumar', 'ayat' => 75],
                ['surat-ke' => 40, 'nama' => 'Ghafir', 'ayat' => 85],
            ],
            23 => [
                ['surat-ke' => 36, 'nama' => 'Yasin', 'ayat' => 83],
                ['surat-ke' => 37, 'nama' => 'As-Saffat', 'ayat' => 182],
                ['surat-ke' => 38, 'nama' => 'Sad', 'ayat' => 88],
            ],
            22 => [
                ['surat-ke' => 33, 'nama' => 'Al-Ahzab', 'ayat' => 73],
                ['surat-ke' => 34, 'nama' => 'Saba’', 'ayat' => 54],
                ['surat-ke' => 35, 'nama' => 'Fatir', 'ayat' => 45],
            ],
            21 => [
                ['surat-ke' => 29, 'nama' => 'Al-‘Ankabut', 'ayat' => 69],
                ['surat-ke' => 30, 'nama' => 'Ar-Rum', 'ayat' => 60],
                ['surat-ke' => 31, 'nama' => 'Luqman', 'ayat' => 34],
                ['surat-ke' => 32, 'nama' => 'As-Sajdah', 'ayat' => 30],
            ],
            20 => [
                ['surat-ke' => 27, 'nama' => 'An-Naml', 'ayat' => 93],
                ['surat-ke' => 28, 'nama' => 'Al-Qasas', 'ayat' => 88],
                ['surat-ke' => 29, 'nama' => 'Al-‘Ankabut', 'ayat' => 45],
            ],
            19 => [
                ['surat-ke' => 25, 'nama' => 'Al-Furqan', 'ayat' => 77],
                ['surat-ke' => 26, 'nama' => 'Asy-Syu‘ara’', 'ayat' => 227],
                ['surat-ke' => 27, 'nama' => 'An-Naml', 'ayat' => 55],
            ],
            18 => [
                ['surat-ke' => 23, 'nama' => 'Al-Mu’minun', 'ayat' => 118],
                ['surat-ke' => 24, 'nama' => 'An-Nur', 'ayat' => 64],
                ['surat-ke' => 25, 'nama' => 'Al-Furqan', 'ayat' => 20],
            ],
            17 => [
                ['surat-ke' => 21, 'nama' => 'Al-Anbiya’', 'ayat' => 112],
                ['surat-ke' => 22, 'nama' => 'Al-Hajj', 'ayat' => 78],
            ],
            16 => [
                ['surat-ke' => 18, 'nama' => 'Al-Kahf', 'ayat' => 110],
                ['surat-ke' => 19, 'nama' => 'Maryam', 'ayat' => 98],
                ['surat-ke' => 20, 'nama' => 'Ta-Ha', 'ayat' => 135],
            ],
            15 => [
                ['surat-ke' => 17, 'nama' => 'Al-Isra’', 'ayat' => 111],
                ['surat-ke' => 18, 'nama' => 'Al-Kahf', 'ayat' => 74],
            ],
            14 => [
                ['surat-ke' => 15, 'nama' => 'Al-Hijr', 'ayat' => 99],
                ['surat-ke' => 16, 'nama' => 'An-Nahl', 'ayat' => 128],
            ],
            13 => [
                ['surat-ke' => 12, 'nama' => 'Yusuf', 'ayat' => 111],
                ['surat-ke' => 13, 'nama' => 'Ar-Ra’d', 'ayat' => 43],
                ['surat-ke' => 14, 'nama' => 'Ibrahim', 'ayat' => 52],
            ],
            12 => [
                ['surat-ke' => 11, 'nama' => 'Hud', 'ayat' => 123],
                ['surat-ke' => 12, 'nama' => 'Yusuf', 'ayat' => 52],
            ],
            11 => [
                ['surat-ke' => 9, 'nama' => 'At-Taubah', 'ayat' => 129],
                ['surat-ke' => 10, 'nama' => 'Yunus', 'ayat' => 109],
                ['surat-ke' => 11, 'nama' => 'Hud', 'ayat' => 5],
            ],
            10 => [
                ['surat-ke' => 20, 'nama' => 'Taha', 'ayat' => 135],
                ['surat-ke' => 21, 'nama' => 'Al-Anbiya', 'ayat' => 112],
                ['surat-ke' => 22, 'nama' => 'Al-Hajj', 'ayat' => 78],
            ],
            9 => [
                ['surat-ke' => 18, 'nama' => 'Al-Kahfi', 'ayat' => 110],
                ['surat-ke' => 19, 'nama' => 'Maryam', 'ayat' => 98],
            ],
            8 => [
                ['surat-ke' => 13, 'nama' => 'Ar-Ra’d', 'ayat' => 43],
                ['surat-ke' => 14, 'nama' => 'Ibrahim', 'ayat' => 52],
                ['surat-ke' => 15, 'nama' => 'Al-Hijr', 'ayat' => 99],
                ['surat-ke' => 16, 'nama' => 'An-Nahl', 'ayat' => 128],
            ],
            7 => [
                ['surat-ke' => 11, 'nama' => 'Hud', 'ayat' => 123],
                ['surat-ke' => 12, 'nama' => 'Yusuf', 'ayat' => 111],
            ],
            6 => [
                ['surat-ke' => 7, 'nama' => 'Al-A’raf', 'ayat' => 206],
                ['surat-ke' => 8, 'nama' => 'Al-Anfal', 'ayat' => 75],
                ['surat-ke' => 9, 'nama' => 'At-Taubah', 'ayat' => 129],
            ],
            5 => [
                ['surat-ke' => 4, 'nama' => 'An-Nisa', 'ayat' => 176],
                ['surat-ke' => 5, 'nama' => 'Al-Maidah', 'ayat' => 120],
            ],
            4 => [
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 141],
            ],
            3 => [
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 253],
            ],
            2 => [
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 142],
            ],
            1 => [
                ['surat-ke' => 1, 'nama' => 'Al-Fatihah', 'ayat' => 7],
                ['surat-ke' => 2, 'nama' => 'Al-Baqarah', 'ayat' => 141],
            ],
        ];

        if (is_null($juz)) {
            return response()->json($juzData);
        }

        return response()->json($juzData[$juz]);
    }
}
