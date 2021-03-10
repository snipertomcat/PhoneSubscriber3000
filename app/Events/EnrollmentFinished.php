<?php

namespace Apithy\Events;

use Apithy\Enrollment;
use Apithy\Experience;
use Apithy\User;

class EnrollmentFinished
{
    /**
     * ExperienceEnrolled constructor.
     *
     * @param Enrollment $enrollment
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment=$enrollment;
    }
}
