<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{


        protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
{
    // Exécution chaque nuit à 02h00
    $schedule->command('golda:import')->dailyAt('11:03')
    ->appendOutputTo(storage_path('logs/golda_schedule.log'))
        ->timezone('Europe/Paris');
}



    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

}
