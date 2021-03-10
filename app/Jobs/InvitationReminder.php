<?php

namespace Apithy\Jobs;

use Apithy\Mail\Invitation as InvitationMail;
use Apithy\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class InvitationReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $invitation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->invitation) {
            if ($this->invitation->status == Invitation::INVITATION_PENDING) {
                Mail::to($this->invitation->contact)->send(new InvitationMail($this->invitation));
            }
        }
    }
}
