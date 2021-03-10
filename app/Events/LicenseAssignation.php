<?php

namespace Apithy\Events;

use Apithy\ExperienceLicence;

class LicenseAssignation
{

    public $license;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ExperienceLicence $license)
    {
        $this->license = $license;
    }
}
