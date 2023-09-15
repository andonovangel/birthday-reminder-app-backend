<?php

namespace App\Jobs;

use App\Mail\BirthdayMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBirthdayReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $birthdays = [];
        if ($this->user){
            $birthdays = $this->user->usersBirthdayReminders()->get();

            foreach ($birthdays as $birthday) {
                $birthdayDate = date('Y-m-d H', strtotime($birthday->birthday_date));
                if ($birthdayDate <= date('Y-m-d H')) {
                    $birthday->birthday_date = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($birthday->birthday_date)));
                    $birthday->update();
                    Mail::to($this->user->email)->send(new BirthdayMail($birthday->name));
                }
            }
        }
    }
}
