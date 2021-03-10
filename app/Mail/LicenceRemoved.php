<?php

namespace Apithy\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class LicenceRemoved extends Mailable implements ShouldQueue
{
    use Queueable;

    public $email;
    public $company_logo;
    public $message;

    /**
     * Create a new message instance.
     *
     * @param $email
     * @param $company_logo
     */

    public function __construct($email, $company_logo, $message = 'Ups... tu invitación ha expirado')
    {
        $this->email = $email;
        $this->company_logo = $company_logo;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->view(
            'mails.html.remove_invitation',
            [
                'email' => $this->email,
                'logo' => $this->company_logo
            ]
        )
            ->subject($this->message);
    }
}
