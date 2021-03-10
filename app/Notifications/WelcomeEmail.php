<?php

namespace Apithy\Notifications;

use Apithy\Services\AutoLoginService;
use Apithy\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\AwsSns\SnsMessage;

class WelcomeEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (!$notifiable->contact_preference) {
            return ['mail'];
        }

        if ($notifiable->contact_preference == "sms") {
            return getSMSChannel();
        }

        if ($notifiable->contact_preference == "whatsapp") {
            return [TwilioChannel::class];
        }

        return [$notifiable->contact_preference];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    public function toMail($notifiable)
    {
        if ($notifiable->registrationMethodIs(User::REGISTRATION_ADMIN)
            || $notifiable->registrationMethodIs(User::REGISTRATION_IMPORT_FILE)) {
            $login_link = $this->getAutoLoginUrl($notifiable);
        } else {
            $login_link = false;
        }


        return (new MailMessage)
            ->view("mails.html.welcome", [
                'login_link' => $login_link,
                'full_name' => $notifiable->full_name,
                'company_name' => $notifiable->company[0]->name,
                'url' => $this->getUrl($notifiable)
            ])
            ->subject('Bienvenido a Apithy');
    }

    public function toTwilio($notifiable)
    {
        $msj = "Te damos la bienvenida a apithy, donde podrás crecer de una manera divertida y a tu ritmo.
        \n ¡Comienza ya! \n {$this->getUrl($notifiable)}";

        $from = "+19165426722";

        if ($notifiable->contact_preference == "sms") {
            $from = "+19165426722";
        }

        if ($notifiable->contact_preference == "whatsapp") {
            $from = 'whatsapp:+19165426722';
        }

        return (new TwilioSmsMessage())
            ->content($msj)->from($from);
    }

    public function toSns($notifiable)
    {
        $msj = "Te damos la bienvenida a apithy, donde podrás crecer de una manera divertida y a tu ritmo.
        \n ¡Comienza ya! \n {$this->getUrl($notifiable)}";

        $from = "+19165426722";

        if ($notifiable->contact_preference == "sms") {
            $from = "+19165426722";
        }

        return SnsMessage::create()
            ->body($msj)
            ->transactional()
            ->sender('apithy');
    }

    public function getAutoLoginUrl($notifiable)
    {
        /*
        $autoLoginService = new AutoLoginService();
        $autoLogin = $autoLoginService->createAutoLogin(
            $notifiable,
            $this->getUrl($notifiable, 'profile/security')
        );
        */
        return $this->getUrl($notifiable, "set-password/{$notifiable->passwordRenew->token}");
    }

    private function getUrl($notifiable, $path = 'experiences')
    {
        $url = route('experiences.index');

        $https = env('APP_HTTPS', false);

        if ($notifiable->company()->count()) {
            $company = $notifiable->company[0];

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

            $url = "$protocol_part{$company->account_name}.{$env}";
            $url .= "apithy.com/{$path}";
        }

        return $url;
    }
}
