<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek kalau user login
        if (Auth::check()) {
            // Jika model User
            if (Auth::user() instanceof \App\Models\User) {
                Auth::user()->update(['last_seen' => now()]);
            }
        }

        return $next($request);
    }
}
