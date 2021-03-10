<?php

namespace Apithy\Notifications;

use Apithy\SmsVerification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Notification;

class ConfirmPhone extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {

        $code = SmsVerification::where('user_id', $notifiable->id)
            ->where('status', 'pending')
            ->latest()->first();

        /*
        $sms_msj = "Gracias por registrarte, tu código de verificación es:
        #code#, o puedes validar tu cuenta en este link:
        " . route("auth.confirm_sms", [$code->code]);

        $msj = str_replace("#code#", $code->code, $sms_msj);
*/
        $msj = "Your registration code is ".$code->code;

        return (new TwilioSmsMessage())
            ->content($msj)->from('whatsapp:+14155238886');
    }
}
