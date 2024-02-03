<?php

namespace App\Console;

use App\Jobs\SendBirthdayReminderEmail;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        collect(User::all())->each(function ($user) use ($schedule) {
            $schedule->job(new SendBirthdayReminderEmail($user))->everyMinute();
        });
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
