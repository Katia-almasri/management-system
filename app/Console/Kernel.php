<?php

namespace App\Console;

use App\Jobs\checkExpirationDate;
use App\Jobs\dailyWarehouseReport;
use App\Jobs\predictions;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->job(new checkExpirationDate)->dailyAt('13:00');
        // $schedule->job(new checkExpirationDate)->everyFiveMinutes();
        // $schedule->job(new dailyWarehouseReport)->everyFiveMinutes();
        // $schedule->job(new predictions)->monthlyOn(28, '12:00');
        $schedule->job(new predictions)->everyFiveMinutes();
        // $schedule->job(new dailyWarehouseReport)->dailyAt('20:00');
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
