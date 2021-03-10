<?php

namespace Apithy\Jobs;

use Apithy\ExperienceLicence;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessExpiredLicenses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $license;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($license)
    {
        $this->license = $license;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now()->startOfDay();
        $license = $this->license;

        if (isset($license->expiration_ends)) {
            if (!!$license->expiration_active && $license->status === ExperienceLicence::STATUS_WAITING_CONFIRMATION) {
                $license->status = ExperienceLicence::STATUS_AVAILABLE;
                $license->email = null;
                $license->expiration_active = 0;
                $license->expiration_start = null;
                $license->expiration_ends = null;
                $license->day_left = null;
                $license->save();
            }
        }
    }
}
