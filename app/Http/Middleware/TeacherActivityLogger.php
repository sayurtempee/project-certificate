<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TeacherActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check() && Auth::user()->role === 'teacher') {
            $action = $request->route()->getName();
            $description = match ($action) {
                'student.create'  => 'Membuka halaman tambah murid',
                'student.store'   => 'Menambahkan murid baru',
                'student.update'  => 'Mengupdate data murid',
                'student.destroy' => 'Menghapus data murid',
                'student.index'   => 'Melihat daftar murid',
                'login'           => 'Membuka halaman login',
                'login.process'   => 'User melakukan login',
                'logout'          => 'User melakukan logout',
                default           => 'Aksi lain',
            };
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action ?? 'unknown',
                'description' => $description,
            ]);
        }
        return $response;
    }
}
