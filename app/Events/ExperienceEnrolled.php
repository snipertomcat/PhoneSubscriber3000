<?php

namespace Apithy\Events;

use Apithy\Experience;
use Apithy\User;

class ExperienceEnrolled
{
    public $experience;
    public $user;

    /**
     * ExperienceEnrolled constructor.
     *
     * @param Experience $experience
     * @param User $user
     * @return void
     */
    public function __construct(Experience $experience, User $user)
    {
        $this->experience = $experience;
        $this->user = $user;
    }
}
