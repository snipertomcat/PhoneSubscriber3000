<?php
namespace Apithy\Listeners;

use Apithy\Events\ExperienceEnrollUpdate;
use Apithy\Mail\EnrollUpdateMail;
use Illuminate\Support\Facades\Mail;

class StudentExperienceEnrollUpdate
{
    /**
     * @param ExperienceEnrollUpdate $event
     */
    public function handle(ExperienceEnrollUpdate $event)
    {
        /*
        Mail::to($event->user->email)
            ->queue(new EnrollUpdateMail($event->experience, $event->user, $event->message));
        */
    }
}
