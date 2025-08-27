<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
                'name' => 'required|string|max:255',
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

        $teacher->name = $validateData['name'];
        $teacher->email = $validateData['email'];

        if (!empty($validateData['password'])) {
            $teacher->password = Hash::make($validateData['password']);
        }

        $teacher->save();

        return $this->redirectToRole('Guru berhasil diperbarui');
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
}
