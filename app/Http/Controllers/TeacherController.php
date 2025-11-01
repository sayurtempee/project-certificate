<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'required|string|min:6|confirmed'
            ],
            [
                'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.min' => 'Password minimal 6 karakter.'
            ]
        );

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'photo' => $photoPath,
            'password' => Hash::make($validateData['password']),
            'role' => 'teacher'
        ]);

        return $this->redirectToRole('Guru berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $teacher)
    {
        $validateData = $request->validate(
            [
                'name' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'nullable|string|min:6|confirmed'
            ],
            [
                'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.min' => 'Password minimal 6 karakter.'
            ]
        );

        // Handle photo
        if ($request->hasFile('photo')) {
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $teacher->photo = $request->file('photo')->store('photos', 'public');
        }

        // ---- Crop foto dari Cropper.js (base64) ----
        if ($request->filled('cropped_image')) {
            $imageData = $request->cropped_image;
            $image = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = 'teacher_photos/' . uniqid() . '.png';

            Storage::disk('public')->put($imageName, base64_decode($image));

            // Hapus foto lama
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $teacher->photo = $imageName;
        }
        // ---- Upload foto biasa tanpa crop ----
        elseif ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $photoPath = $request->file('photo')->store('teacher_photos', 'public');
            $teacher->photo = $photoPath;
        }


        // $teacher->name = $validateData['name'];
        $teacher->email = $validateData['email'];

        if (!empty($validateData['password'])) {
            $teacher->password = Hash::make($validateData['password']);
        }

        $teacher->save();

        // return $this->redirectToRole('Guru berhasil diperbarui');
        return redirect()->route('teacher.edit', $teacher->id)->with('success', 'Data Guru berhasil diperbarui!');
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
        $rows = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!$rows) {
            return back()->with('error', 'File kosong atau tidak bisa dibaca.');
        }

        // Hilangkan BOM (Byte Order Mark)
        $rows[0] = preg_replace('/^\xEF\xBB\xBF/', '', $rows[0]);
        $csvData = array_map('str_getcsv', $rows);

        // Hapus header jika ada
        $first = array_map('strtolower', array_map('trim', $csvData[0] ?? []));
        if (in_array('nama', $first) && in_array('no_induk', $first)) {
            array_shift($csvData);
        }

        $normalize = fn($text) => strtolower(str_replace(['-', ' '], '', $text));
        $juzData = $this->getJuzDataInternal();
        $currentUser = Auth::user();

        $skipped = [];
        $success = 0;

        try {
            DB::beginTransaction();

            foreach ($csvData as $index => $row) {
                $nama        = trim($row[0] ?? '');
                $noInduk     = trim($row[1] ?? '');
                $juz         = isset($row[2]) && is_numeric($row[2]) ? (int)$row[2] : 0;
                $namaSurat   = trim($row[3] ?? '');
                $kelancaran  = isset($row[4]) && is_numeric($row[4]) ? (int)$row[4] : 0;
                $fasohah     = isset($row[5]) && is_numeric($row[5]) ? (int)$row[5] : 0;
                $tajwid      = isset($row[6]) && is_numeric($row[6]) ? (int)$row[6] : 0;

                // Skip jika data penting kosong
                if ($nama === '' || $noInduk === '' || $namaSurat === '' || $juz <= 0) {
                    $skipped[] = "Baris #$index: data tidak lengkap";
                    continue;
                }

                $oldStudent = Student::where('no_induk', $noInduk)->first();

                // Skip jika student sudah dimiliki guru lain
                if ($oldStudent && $oldStudent->user_id !== $currentUser->id) {
                    $skipped[] = "Baris #$index: $nama ($noInduk) sudah dimiliki guru lain";
                    continue;
                }

                $penyimak = $oldStudent->penyimak ?? ($currentUser->role === 'teacher' ? $currentUser->name : null);

                // Simpan / update student
                $student = Student::updateOrCreate(
                    ['no_induk' => $noInduk],
                    [
                        'nama'         => $nama,
                        'juz'          => max(0, min(30, $juz)),
                        'penyimak'     => $penyimak,
                        'tahun_ajaran' => now()->year,
                        'user_id'      => $currentUser->id,
                    ]
                );

                // Pastikan JUZ valid
                if (!isset($juzData[$juz])) {
                    $skipped[] = "Baris #$index: Juz $juz tidak ditemukan dalam konfigurasi";
                    continue;
                }

                // Cari surat dalam daftar Juz
                $suratConfig = collect($juzData[$juz])->first(function ($item) use ($normalize, $namaSurat) {
                    return $normalize($item['nama_surat']) === $normalize($namaSurat);
                });

                if (!$suratConfig) {
                    $skipped[] = "Baris #$index: Surat '$namaSurat' tidak valid untuk Juz $juz";
                    continue;
                }

                $total = $kelancaran + $fasohah + $tajwid;
                $bobot = $this->getBobotByJuz($juz);
                $nilai = max(0, 100 - ($total * $bobot));
                $predikat = $this->getPredikat($nilai);

                $student->surats()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'surat_ke'   => $suratConfig['surat_ke'],
                    ],
                    [
                        'nama_surat'      => $suratConfig['nama_surat'],
                        'ayat'            => $suratConfig['ayat'],
                        'kelancaran'      => $kelancaran,
                        'fasohah'         => $fasohah,
                        'tajwid'          => $tajwid,
                        'total_kesalahan' => $total,
                        'nilai'           => $nilai,
                        'predikat'        => $predikat,
                    ]
                );

                $success++;
            }

            DB::commit();

            $message = "CSV berhasil diimpor! ($success baris sukses)";
            if (count($skipped) > 0) {
                $message .= " — " . count($skipped) . " baris dilewati.";
            }

            return redirect()->back()->with('success', $message)->with('skipped', $skipped);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat impor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacher)
    {
        // Hapus foto jika ada
        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return $this->redirectToRole('Guru berhasil dihapus', 'deleted');
    }

    public function deletePhoto($id)
    {
        $teacher = User::findOrFail($id);

        // cek apakah teacher benar
        if ($teacher->role !== 'teacher') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if ($teacher->photo && file_exists(public_path('storage/' . $teacher->photo))) {
            unlink(public_path('storage/' . $teacher->photo));
        }

        $teacher->photo = null;
        $teacher->save();

        return redirect()->route('teacher.edit', $teacher->id)->with('success', 'Foto berhasil dihapus.');
    }

    /**
     * Redirect user berdasarkan role login.
     */
    protected function redirectToRole($message = null, $sessionKey = 'success')
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                $route = route('teacher.index'); // route dashboard admin
                break;
            case 'teacher':
                $route = route('dashboard'); // route dashboard teacher
                break;
            default:
                $route = route('home'); // fallback
        }

        return $message
            ? redirect($route)->with($sessionKey, $message)
            : redirect($route);
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
