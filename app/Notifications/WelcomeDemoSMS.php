<?php

namespace Apithy\Notifications;

use Apithy\Services\AutoLoginService;
use Apithy\User;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioMmsMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioSmsMessage;

class WelcomeDemoSMS extends Notification
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
        $from = "+19165426722";
        //$user_password = str_random(6);
        //$notifiable->password=bcrypt($user_password);
        $notifiable->save();
        $login_link = $this->autoLogin($notifiable, "experiences");
        //$url = getEnvCompanyURL($notifiable);
        $msj="Hola {$notifiable->name}!.\n\napithy forma personas a través de un ambiente digital adaptativo (inteligencia artificial).\n\nGarantizamos la formación de tu equipo y el aprendizaje a través experiencias, y además ahorramos dinero y tiempo.\n\nDale la bienvenida a la formación para la industria 4.0.\n\nIngresa a nuestro demo con el siguiente enlace : {$login_link}";
        Log::info('Twilio Message: '.$msj);

        return (new TwilioSmsMessage())->content($msj)
            ->from($from);
    }

    private function autoLogin(User $user, $url)
    {
        $autoLoginService = new AutoLoginService();
        $url = url($url);
        $autoLogin = $autoLoginService->createAutoLogin($user, $url);
        return $autoLoginService->urlGenerator($autoLogin);
    }
}
