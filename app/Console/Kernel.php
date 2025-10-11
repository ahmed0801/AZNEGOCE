<?php

namespace App\Console;

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


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Test : exécution chaque minute + log
        $schedule->command('golda:import')
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/golda_schedule.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

}
