<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ScheduledCommand;
use agoalofalife\Reports\Console\ParseReportsCommand;
// use App\Task;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        //This is the line of code added, at the end, we the have class name of ScheduledCommand.php inside app\console\commands
        '\App\Console\Commands\ScheduledCommand',
        '\App\Console\Commands\CanceledCommand',
        // '\App\Console\Commands\BackupDatabase',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notifications:SchedueledShipment')
            ->everyMinute();
        $schedule->command('Canceled:CanceledShipments')
            ->everyMinute();

        $schedule->command('queue:work --force --tries=5')
            ->everyMinute()
            //->withoutOverlapping()
            ->sendOutputTo(storage_path('queue-work.log'));

        // $schedule->command('db:backup')->mondays()->at('23:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
