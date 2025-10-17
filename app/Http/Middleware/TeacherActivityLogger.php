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
            $routeName = $request->route()?->getName();
            if ($routeName) {
                $description = match ($routeName) {
                    'student.create'  => 'Membuka halaman tambah murid',
                    'student.store'   => 'Menambahkan murid baru',
                    'student.update'  => 'Mengupdate data murid',
                    'student.destroy' => 'Menghapus data murid',
                    'student.index'   => 'Melihat daftar murid',
                    'login'           => 'Membuka halaman login',
                    'login.process'   => 'User melakukan login',
                    'logout'          => 'User melakukan logout',
                    'dashboard'       => 'Melihat isi dashboard',
                    'student.show'    => 'Melihat detail murid',
                    'student.updateInline' => 'Mengupdate data murid secara inline',
                    'student.pdf'     => 'Mendownload PDF laporan murid',
                    'certificates.index'   => 'Melihat daftar sertifikat murid',
                    'certificates.showCertificate'     => 'Melihat detail sertifikat murid',
                    'certificates.downloadCertificate' => 'Mendownload sertifikat murid',
                    'certificates.updateNamaKepsek'    => 'Mengupdate Nama Kepala Sekolah',
                    'certificates.updateNipKepsek'     => 'Mengupdate NIP Kepala Sekolah',
                    'certificates.updateTempatKelulusan' => 'Menetapkan Tempat Kelulusan',
                    'certificates.updateTanggalLulus' => 'Meneteapkan Tanggal Kelulusan',
                    default           => 'Mengakses halaman {$routeName}',
                };
            }
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $routeName,
                'description' => $description,
            ]);
        }
        return $response;
    }
}
