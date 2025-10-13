<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{


        protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
{
    // Exécution chaque nuit à 02h00
    $schedule->command('golda:import')->dailyAt('16:23');
}



    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

}
