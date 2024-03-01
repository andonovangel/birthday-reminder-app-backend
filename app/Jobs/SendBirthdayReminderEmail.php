<?php

namespace App\Jobs;

use App\Mail\BirthdayMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
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
        $birthdays = $this->user->usersBirthdayReminders()->get();

        foreach ($birthdays as $birthday) {
            $birthdayDate = date('m-d', strtotime($birthday->birthday_date));
            if ($birthdayDate == date('m-d')) {
                Mail::to($this->user->email)->send(new BirthdayMail($birthday->name, $birthday->body, $birthday->phone_number));
            }
        }
    }
}
