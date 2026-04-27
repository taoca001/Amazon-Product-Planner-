<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--keep=7 : Number of days to keep backups}';

    protected $description = 'Create a timestamped backup of the SQLite database';

    public function handle(): int
    {
        $dbPath = database_path('database.sqlite');

        if (!File::exists($dbPath)) {
            $this->error('SQLite database not found at: ' . $dbPath);
            return self::FAILURE;
        }

        $backupDir = storage_path('backups');
        File::ensureDirectoryExists($backupDir);

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupFile = $backupDir . "/database_{$timestamp}.sqlite";

        File::copy($dbPath, $backupFile);

        $sizeMB = round(File::size($backupFile) / 1024 / 1024, 2);
        $this->info("Backup created: {$backupFile} ({$sizeMB} MB)");

        // Alte Backups aufräumen
        $keep = (int) $this->option('keep');
        $cutoff = Carbon::now()->subDays($keep);
        $deleted = 0;

        foreach (File::glob($backupDir . '/database_*.sqlite') as $file) {
            if (File::lastModified($file) < $cutoff->timestamp) {
                File::delete($file);
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->info("Deleted {$deleted} old backup(s) (older than {$keep} days).");
        }

        return self::SUCCESS;
    }
}
