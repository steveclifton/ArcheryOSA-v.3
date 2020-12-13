<?php

namespace App\Console;

use App\Jobs\SendExceptionEmail;
use App\Models\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            // Where open or in progress
            $events = Event::whereIn('eventstatusid', [1, 2])
                ->where('end', '<', date('Y-m-d'))
                ->get();

            if (empty($events)) {
                return null;
            }
            $log = '';

            foreach ($events as $event) {
                // Set to completed
                $event->eventstatusid = 4;
                $event->save();

                $log .= 'Completed Event : ' . $event->label . ' (Eventid: '. $event->eventid . ')' . PHP_EOL;
            }

            if (!empty($log)) {
                Log::info($log);
            }

        })->daily();

        $schedule->call(function () {
            $tidyHq = new \App\Http\Classes\TidyHQ();
            $tidyHq->updateAllContacts();
            $tidyHq->updateMemberships();

        })->everySixHours();

        $schedule->call(function() {
            $e = DB::table('exceptions')->get();

            if ($e->isEmpty()) {
                return null;
            }
            Log::info($e);

            $exceptions = '';
            $count = 1;
            foreach ($e as $item) {
                $exceptions .= $count++ . ': ' . $item->message . '<br>';
                $exceptions .= 'File : ' . $item->file . '<br><br>';
            }

            if (!empty($exceptions)) {
                SendExceptionEmail::dispatch($exceptions, 'ArcheryOSA Exceptions');
            }

            DB::table('exceptions')->delete();
        })->everyFiveMinutes();


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
