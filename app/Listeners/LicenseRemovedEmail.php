<?php

namespace Apithy\Listeners;

use Apithy\Events\LicenseRemoved;
use Apithy\Mail\LicenceRemoved;
use Apithy\Utilities\Master\Master;
use Illuminate\Support\Facades\Mail;

class LicenseRemovedEmail
{
    /**
     * Handle
     *
     * @param LicenseRemoved $event
     * @return void
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function handle(LicenseRemoved $event)
    {
        if (Master::isPhone($event->email)) {
            $client = Master::getTwilioClient();
            $client->messages->create("+52{$event->email}", [
                'from' => Master::getTwilioFromPhone(),
                'body' => "apithy aNGLE. \n {$event->message}"
            ]);
        } else {
            Mail::to($event->email)
                ->queue(new LicenceRemoved($event->email, $event->company_logo, $event->message));
        }
    }
}
