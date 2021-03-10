<?php

namespace Apithy\Events;

use Apithy\Invitation;
use Illuminate\Queue\SerializesModels;

class InvitationResend
{
    use SerializesModels;

    public $invitation;

    /**
     * Create a new event instance.
     *
     * @param Invitation $invitation
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
