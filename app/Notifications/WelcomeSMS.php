<?php

namespace Apithy\Notifications;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\AwsSns\SnsMessage;

class WelcomeSMS extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return getSMSChannel();
    }

    public function toTwilio($notifiable)
    {
        $user_password = Str::random(6);
        $notifiable->password=bcrypt($user_password);
        $notifiable->save();

        $url = getEnvCompanyURL($notifiable);

        $from = "+19165426722";
        $msj = "¡Hola {$notifiable->name}!.\n Ya puedes entrar a ".$url.
            " e iniciar sesión con tu número celular y contraseña temporal: {$user_password} bienvenido a Apithy";

        Log::info('Twilio Whastapp Message: '.$msj);

        return (new TwilioSmsMessage())->content($msj)->from($from);
    }

    public function toSns($notifiable)
    {
        $user_password = Str::random(6);
        $notifiable->password=bcrypt($user_password);
        $notifiable->save();

        $url = getEnvCompanyURL($notifiable);

        $from = "+19165426722";
        $msj = "¡Hola {$notifiable->name}!.\n Ya puedes entrar a ".$url.
            " e iniciar sesión con tu número celular y contraseña temporal: {$user_password} bienvenido a Apithy";

        Log::info('Twilio Whastapp Message: '.$msj);

        return SnsMessage::create()
        ->body($msj)
        ->transactional()
        ->sender('apithy');
    }
}
