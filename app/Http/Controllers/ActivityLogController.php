<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $logs = ActivityLog::with('user')
            ->whereHas('user', function ($q) use ($search) {
                $q->where('role', 'teacher');
                if ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                }
            })
            ->latest()
            ->get();

        return view('activity_logs.index', compact('logs', 'search'));
    }

    public function backupPdf()
    {
        $logs = ActivityLog::with('user')
            ->whereHas('user', function ($q) {
                $q->where('role', 'teacher');
            })
            ->latest()
            ->get();

        $pdf = Pdf::loadView('activity_logs.pdf', compact('logs'));

        return $pdf->download('activity_logs_' . now()->format('Y_m_d_H_i_s') . '.pdf');
    }


    public function clear()
    {
        ActivityLog::truncate(); // hapus semua isi tabel
        return redirect()->route('activity.logs.index')->with('success', 'Semua activity logs berhasil dihapus.');
    }
}
