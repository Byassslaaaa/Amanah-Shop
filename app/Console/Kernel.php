<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Auto-update shipping tracking every 2 hours
        $schedule->command('tracking:update')
            ->everyTwoHours()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/tracking-updates.log'));

        // Check for overdue installment payments daily at midnight
        $schedule->command('credit:check-overdue')
            ->daily()
            ->at('00:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/overdue-checks.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
