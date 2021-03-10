<?php

namespace Apithy\Events;

use Apithy\Experience;
use Apithy\Invitation;
use Apithy\User;
use Illuminate\Queue\SerializesModels;

class InvitationCreated
{
    use SerializesModels;

    public $invitation;
    public $experience;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param Invitation $invitation
     * @param Experience $experience
     * @param User $user
     */
    public function __construct(Invitation $invitation, $experience = null, $user = null)
    {
        $this->invitation = $invitation;
        $this->experience = $experience;
        $this->user = $user;
    }
}
