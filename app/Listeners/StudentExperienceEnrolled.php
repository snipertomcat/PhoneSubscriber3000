<?php

namespace Apithy\Listeners;

use Apithy\Events\ExperienceEnrolled;
use Apithy\Mail\EnrollMail;
use Illuminate\Support\Facades\Mail;

class StudentExperienceEnrolled
{
    /**
     * Handle
     *
     * @param ExperienceEnrolled $event
     * @return void
     */
    public function handle(ExperienceEnrolled $event)
    {
        /*
        Mail::to($event->user->email)
            ->queue(new EnrollMail($event->experience, $event->user));
        */
    }
}
