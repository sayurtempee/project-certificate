<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    private function logActivity($userId, $action, $description)
    {
        ActivityLog::create([
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
        ]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showForgotForm()
    {
        // log buka halaman forgot password
        if (Auth::check() && Auth::user()->role === 'teacher') {
            $this->logActivity(Auth::id(), 'forgot_password', 'Membuka halaman lupa password');
        }
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // log kirim reset link
        if (Auth::check() && Auth::user()->role === 'teacher') {
            $this->logActivity(Auth::id(), 'forgot_password', 'Mengirim link reset password ke email: ' . $request->email);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token)
    {
        // log buka halaman reset password
        if (Auth::check() && Auth::user()->role === 'teacher') {
            $this->logActivity(Auth::id(), 'reset_password', 'Membuka halaman reset password dengan token: ' . $token);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
                'email' => 'required|string|email',
                'password' => 'required|string|confirmed|min:6',
            ],
            [
                'password.min' => 'Minimal Password 6 Karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai',
                'email.email' => 'Format email tidak valid',
            ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        // log reset password
        if (Auth::check() && Auth::user()->role === 'teacher') {
            $this->logActivity(Auth::id(), 'reset_password', 'Melakukan reset password untuk email: ' . $request->email);
        }

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
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

            // log login
            if (Auth::check() && Auth::user()->role === 'teacher') {
                $this->logActivity(Auth::id(), 'login', 'User melakukan login');
            }

            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
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

        if (Auth::check() && Auth::user()->role === 'teacher') {
            $this->logActivity(Auth::id(), 'logout', 'User melakukan logout');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Anda telah berhasil logout.');
    }
}
