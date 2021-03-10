<?php

namespace Apithy\Listeners;

use Apithy\Events\InvitationCreated;

class SendWelcomeEmail
{
    /**
     * Handle the event.
     *
     * @param  InvitationCreated  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->notify(app(\Apithy\Notifications\WelcomeEmail::class));
    }
}
