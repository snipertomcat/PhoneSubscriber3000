<?php

namespace Apithy\Listeners;

use Apithy\Utilities\Master\Master;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use AWS;

class ResendInvitationPhone
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function handle($event)
    {
        $message = "En {$event->invitation->company->name} creemos en ti, por eso te invitamos a apithy.com
        \nRegístrate en {$this->getUrl($event)}";

        $sms_service = getSMSService();
        if ($sms_service == "twilio") {
            $client = Master::getTwilioClient();
            $client->messages->create("+52{$event->invitation->contact}", [
                'from' => Master::getTwilioFromPhone(),
                'body' => $message
            ]);
        } else {
            $sms = AWS::createClient('sns');
            $sms->publish([
                'Message' => $message,
                'PhoneNumber' => "+52{$event->invitation->contact}",
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SMSType' => [
                        'DataType' => 'String',
                        'StringValue' => 'Transactional',
                    ]
                ],
            ]);
        }
    }

    private function getUrl($event)
    {
        $https = env('APP_HTTPS', false);
        $protocol_part = (($https) ? 'https://' : 'http://');
        $env = env('APP_ENV', 'prod');
        $env = (($env != 'prod') ? "{$env}." : '');
        $company = $event->invitation->company()->first();
        $auxReg = "apithy.com/register?register_type=invitation";
        $auxInv = "&invitation_code={$event->invitation->code}";
        $url = "{$protocol_part}{$company->account_name}.{$env}{$auxReg}{$auxInv}";
        return $url;
    }
}
