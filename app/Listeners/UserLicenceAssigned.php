<?php

namespace Apithy\Listeners;

use Apithy\Events\LicenseAssignation;

class UserLicenceAssigned
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LicenseAssignation  $event
     * @return void
     */
    public function handle(LicenseAssignation $event)
    {
        $licence = $event->license;

        if ($licence->user()->first()) {
            $notification = app(\Apithy\Notifications\UserLicenceAssigned::class);
            $licence->notify($notification);
        }
    }
}
