<?php

namespace Apithy\Events;

use Apithy\Experience;
use Apithy\User;

class ExperienceEnrollUpdate
{
    public $experience;
    public $user;
    public $message;

    /**
     * ExperienceEnrollUpdate constructor.
     *
     * @param Experience $experience
     * @param User $user
     * @param String $message
     * @return void
     */
    public function __construct(Experience $experience, User $user, $message)
    {
        $this->experience = $experience;
        $this->user = $user;
        $this->message = $message;
    }
}
