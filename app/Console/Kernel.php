<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Die Artisan Commands für die Anwendung.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\BackupDatabase::class,
        \App\Console\Commands\GenerateApiToken::class,
        \App\Console\Commands\TestSpApiCommand::class,
    ];

    /**
     * Definiere die Befehle für die Anwendung.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('db:backup')->dailyAt('02:00');
    }

    /**
     * Registriere die Commands für die Anwendung.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
