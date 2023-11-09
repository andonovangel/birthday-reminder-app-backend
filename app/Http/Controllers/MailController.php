<?php

namespace App\Http\Controllers;

use App\Mail\BirthdayMail;
use App\Services\BirthdayService;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public $birthdayService;

    function __construct() {
        $this->birthdayService = new BirthdayService();
    }
    public function sendMail() {
        // date_default_timezone_set('Europe/Skopje');
        $user = auth()->user();
        $birthdays = [];
        if ($user){
            $birthdays = $this->birthdayService->findAll();

            foreach ($birthdays as $birthday) {
                $birthdayDate = date('Y-m-d H', strtotime($birthday->birthday_date));
                if ($birthdayDate <= date('Y-m-d H')) {
                    $birthday->birthday_date = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($birthday->birthday_date)));
                    $birthday->update();
                    Mail::to($user->email)->send(new BirthdayMail($birthday->name, $birthday->body));
                }
            }
        }

        return view('welcome');
    }
}
