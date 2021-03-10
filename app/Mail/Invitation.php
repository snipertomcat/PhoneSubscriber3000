<?php

namespace Apithy\Mail;

use Apithy\Invitation as Model;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invitation;

    /**
     * Create a new message instance.
     *
     * @param Model $invitation
     * @return void
     */
    public function __construct(Model $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url('register?register_type=invitation&invitation_code=' .
            $this->invitation->code);

        $https = env('APP_HTTPS', false);

        $logo = asset('images/mails/1391545686622704.png');

        if ($this->invitation->company()->count()) {
            $company = $this->invitation->company;

            $env = env('APP_ENV', 'prod');
            if ($env != 'prod') {
                $env = $env . '.';
            } else {
                $env = '';
            }

            $protocol_part = 'http://';
            $company_part='';

            if ($https) {
                $protocol_part = 'https://';
            }

            $url = $protocol_part . $company->account_name . '.' . $env .
                'apithy.com/register?register_type=invitation&invitation_code=' .
                $this->invitation->code;

            if ($company->hasLogo()) {
                $logo = $company->full_path_logo;
            }
        }


        return $this->view('mails.invitation', [
            'invitation' => $this->invitation,
            'url' => $url,
            'logo' => $logo
        ])
            ->subject('¡Te estamos esperando!');
    }
}
