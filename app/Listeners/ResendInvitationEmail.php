<?php

namespace Apithy\Listeners;

use Apithy\Events\InvitationCreated;
use Apithy\Events\InvitationResend;
use Apithy\Mail\Invitation as InvitationMail;
use Illuminate\Support\Facades\Mail;

class ResendInvitationEmail
{
    /**
     * Handle the event.
     *
     * @param  InvitationCreated  $event
     * @return void
     */
    public function handle(InvitationResend $event)
    {
        Mail::to($event->invitation->contact)->queue(new InvitationMail($event->invitation));
    }
}
