<?php

namespace Apithy\Listeners;

use Apithy\Events\LicenseAssignation;
use Apithy\ExperienceLicence;
use Apithy\Jobs\ProcessExpiredLicenses;
use Apithy\Services\ExperienceLicensesService;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LicenseExpiration
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
        $job = new ProcessExpiredLicenses($event->license);
        dispatch($job)->delay(Carbon::now()->addDays($event->license->day_left));
    }
}
