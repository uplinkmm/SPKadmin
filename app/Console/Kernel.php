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
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:fetch-thai-stock-two-d-command')->everyTwoSeconds();
        $schedule->command('app:fetch-thai-stock-two-d-command')->dailyAt('11:00');
        $schedule->command('app:fetch-thai-stock-two-d-command')->dailyAt('12:01');
        $schedule->command('app:fetch-thai-stock-two-d-command')->dailyAt('15:00');
        $schedule->command('app:fetch-thai-stock-two-d-command')->dailyAt('16:30');
        $schedule->command('app:fetch-thai-stock-modern-internet-command')->dailyAt('09:31');
        $schedule->command('app:fetch-thai-stock-modern-internet-command')->dailyAt('14:01');

        $schedule->command('app:fetch-historical-thai-stock-two-d-command')->dailyAt('16:31');
        $schedule->command('app:save-thai-stock-modern-internet-daily-command')->dailyAt('16:32');
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
