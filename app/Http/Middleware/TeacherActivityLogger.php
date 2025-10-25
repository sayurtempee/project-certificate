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
            $user = Auth::user();
            $routeName = $request->route()?->getName();
            $method = $request->method();

            if ($method == 'GET' && !in_array($routeName, [
                'student.index',
                'dashboard',
                'certificates.index'
            ])) {
                return $response;
            }

            if ($routeName) {
                $description = match ($routeName) {
                    'student.create'  => "{$user->name} Membuka halaman tambah murid",
                    'student.store'   => "{$user->name} Menambahkan murid baru",
                    "students.import" => "{$user->name} Mengimpor data murid",
                    'student.update'  => "{$user->name} Mengupdate data murid",
                    'student.destroy' => "{$user->name} Menghapus data murid",
                    'student.index'   => "{$user->name} Melihat daftar murid",
                    'login'           => "{$user->name} Membuka halaman login",
                    'login.process'   => "{$user->name} melakukan login",
                    'logout'          => "{$user->name} melakukan logout",
                    'dashboard'       => "{$user->name} Melihat isi dashboard",
                    'student.show'    => "{$user->name} Melihat detail murid",
                    'student.updateInline' => "{$user->name} Mengupdate data murid secara inline",
                    'student.pdf'     => "{$user->name} Mendownload PDF laporan murid",
                    'certificates.index'   => "{$user->name} Melihat daftar sertifikat murid",
                    'certificates.showCertificate'     => "{$user->name} Melihat detail sertifikat murid",
                    'certificates.downloadCertificate' => "{$user->name} Mendownload sertifikat murid",
                    'certificates.updateNamaKepsek'    => "{$user->name} Mengupdate Nama Kepala Sekolah",
                    'certificates.updateNipKepsek'     => "{$user->name} Mengupdate NIP Kepala Sekolah",
                    'certificates.updateTempatKelulusan' => "{$user->name} Menetapkan Tempat Kelulusan",
                    'certificates.updateTanggalLulus' => "{$user->name} Menetapkan Tanggal Kelulusan",
                    default           => "{$user->name} Mengakses halaman {$routeName}",
                };
            }

            $recent = ActivityLog::where('user_id', $user->id)
                ->where('action', $routeName)
                ->where('description', $description)
                ->where('created_at', '>=', now()->subSeconds(3))
                ->exists();

            if (!$recent) {
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => $routeName,
                    'description' => $description,
                ]);
            }
        }
        return $response;
    }
}
