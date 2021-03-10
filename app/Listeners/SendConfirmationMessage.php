<?php

namespace Apithy\Listeners;

use Apithy\Events\InvitationCreated;
use Apithy\Mail\UserRegister as RegisterMail;
use Apithy\SmsVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendConfirmationMessage
{
    /**
     * Handle the event.
     *
     * @param  InvitationCreated $event
     * @return void
     */
    public $user;

    public function handle($event)
    {
        $this->user = $event->user;

        if ($this->user->comunication_method == "email") {
            $this->sendConfirmationToUser($this->user);
        } else {
            SmsVerification::sendSmsValidationMessage($this->user);
        }
    }

    protected function sendConfirmationToUser($user)
    {
        // Create the confirmation code
        $user->confirmation_code = Str::random(25);
        $user->save();

        // Notify the user
        $notification = app(config('confirmation.notification'));
        $user->notify($notification);

        $user->notify($notification)->delay(Carbon::now()->addDays(1)->setHour(8)->setMinutes(30)->setSeconds(0));
        $user->notify($notification)->delay(Carbon::now()->addDays(2)->setHour(8)->setMinutes(30)->setSeconds(0));
        $user->notify($notification)->delay(Carbon::now()->addDays(3)->setHour(8)->setMinutes(30)->setSeconds(0));
    }
}
