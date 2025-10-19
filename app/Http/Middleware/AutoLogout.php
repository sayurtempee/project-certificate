<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AutoLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = Session::get('lastActivityTime');
            $now = now();
            $user = Auth::user();

            if ($lastActivity && $now->diffInMinutes($lastActivity) >= 60) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('message', "{$user->name} telah otomatis logout, karena tidak aktif selama 60 menit.");
            }

            // update waktu terakhir aktif
            Session::put('lastActivityTime', $now);
        }
        return $next($request);
    }
}
