<?php

namespace Apithy\Events;

class LicenseRemoved
{

    public $email;
    public $company_logo;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param $email
     * @param string $company_logo
     * @param string $message
     */
    public function __construct($email, $company_logo = '', $message = 'Ups... tu invitación ha expirado')
    {
        $this->email = $email;
        $this->message = $message;
        if (empty($company_logo)) {
            $this->company_logo = asset('images/mails/1391545686622704.png');
        } else {
            $this->company_logo = $company_logo;
        }
    }
}
