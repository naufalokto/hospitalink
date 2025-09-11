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
        // Run database optimization daily at 2 AM
        $schedule->command('db:optimize --analyze-tables')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Run performance tests weekly on Sunday at 3 AM
        $schedule->command('db:performance-test --queries=500 --concurrent=10')
            ->weeklyOn(0, '03:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Run cache performance test daily at 4 AM
        $schedule->command('cache:performance-test --operations=2000')
            ->dailyAt('04:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Clear expired caches every hour
        $schedule->command('cache:clear')
            ->hourly()
            ->withoutOverlapping();

        // Run index monitoring weekly on Monday at 5 AM
        $schedule->command('db:index-monitor --show-unused --show-duplicates')
            ->weeklyOn(1, '05:00')
            ->withoutOverlapping()
            ->runInBackground();
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
