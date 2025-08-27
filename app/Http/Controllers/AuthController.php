<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Kalau user sudah online di tempat lain → tolak
            if ($user->is_online && $user->last_login_token !== null) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini sedang digunakan di perangkat lain.',
                ]);
            }

            // Set status online + simpan last_login_token
            $user->update([
                'is_online' => true,
                'last_login_token' => Str::random(60), // token unik
            ]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->update([
                'is_online' => false,
                'last_login_token' => null, // reset token
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
