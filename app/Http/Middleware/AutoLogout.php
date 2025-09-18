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
            $now = Carbon::now();

            if ($lastActivity && $now->diffInMinutes($lastActivity) >= 60) {
                return app(\App\Http\Controllers\AuthController::class)->logout($request);
            }

            // update waktu terakhir aktif
            Session::put('lastActivityTime', $now);
        }
        return $next($request);
    }
}
