<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use app\Schedules;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\RunPKSchedule::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->hourly();
        $schedule->call('App\Schedules\PkTaskSchedule@run')->dailyAt('11:00');
        $schedule->call('App\Schedules\UserLogSchedule@run')->dailyAt('11:05');
//        $schedule->call('App\Schedules\UserLogSchedule@test')->everyThirtyMinutes();
        //$schedule->call('App\Http\Controllers\QueueController@index')->dailyAt('14:50');
        //$schedule->call('App\Http\Controllers\QueueController@countVoteResult')->dailyAt('15:01');
        //$schedule->call('App\Http\Controllers\QueueController@assignSolution')->dailyAt('05:00');
    }
}
