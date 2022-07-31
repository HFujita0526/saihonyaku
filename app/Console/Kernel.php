<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
        $schedule->call(function () {
            if (!Storage::exists('translation/logs/log_UsedLastDate.txt')) {
                $arrayNext['date'] = (new Carbon())->toDateString();
            } else {
                $arrayNext = json_decode(Storage::get('translation/logs/log_Next.json'), true);
                $arrayNext['date'] = (new Carbon($arrayNext['date']))->addDay()->toDateString();
            }
            $arrayNext['id'] = 1;
            Storage::put('translation/logs/log_Next.json', json_encode($arrayNext));
        })->daily();
        $schedule->command('executetranslation')->everyMinute();
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
