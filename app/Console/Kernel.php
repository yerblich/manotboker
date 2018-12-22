<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
 use Illuminated\Console\WithoutOverlapping;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:testEmail')
        ->appendOutputTo(storage_path() . '/queue.log')
                 ->everyTenMinutes();


                 $schedule->command('queue:work --daemon --once --tries=1 ')
                 ->everyMinute()
                 ->appendOutputTo(storage_path() . '/queue.log');

                 // $schedule->command('queue:work --daemon --tries=3  ')
                 // ->everyMinute()
                 // ->appendOutputTo(storage_path() . '/queue.log')
                 // ->WithoutOverlapping();
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
