<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

class BackupActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activitylogs:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup activity logs harian lalu hapus dari database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->format('Y-m-d');
        $logs = ActivityLog::whereDate('created_at', $today)->get();

        if ($logs->isEmpty()) {
            $this->info("Tidak ada log untuk $today");
            return;
        }

        $filename = "backups/activity_logs_{$today}.csv";
        $handle = fopen(storage_path("app/$filename"), 'w');

        fputcsv($handle, ['id', 'user_id', 'action', 'description', 'created_at']);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->user_id,
                $log->action,
                $log->description,
                $log->created_at,
            ]);
        }
        fclose($handle);
        ActivityLog::whereDate('created_at',  $today)->delete();
        $this->info("Logs berhasil dibackup ke $filename dan dihapus dari database.");
    }
}
