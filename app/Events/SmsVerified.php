<?php

namespace Apithy\Events;

use Apithy\Experience;
use Apithy\Invitation;
use Apithy\User;
use Illuminate\Queue\SerializesModels;

class SmsVerified
{
    use SerializesModels;

    public $smsVerified;
    /**
     * Create a new event instance.
     *
     * @param Invitation $invitation
     * @param Experience $experience
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
