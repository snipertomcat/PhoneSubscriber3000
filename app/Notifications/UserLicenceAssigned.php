<?php

namespace Apithy\Notifications;

use Apithy\Services\AutoLoginService;
use Apithy\Utilities\Master\Master;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\AwsSns\SnsMessage;
use Illuminate\Support\Facades\Log;


class UserLicenceAssigned extends Notification implements ShouldQueue
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
        if (!$notifiable->user->contact_preference) {
            return ['mail'];
        }

        if ($notifiable->user->contact_preference == "sms") {
            return getSMSChannel();
        }

        if ($notifiable->user->contact_preference == "whatsapp") {
            return [TwilioChannel::class];
        }

        return [$notifiable->user->contact_preference];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        if ($notifiable->user) {
            return (new MailMessage)
                ->subject('¡Te estamos esperando!')
                ->view(
                    "mails.html.licence_assigned",
                    [
                        'experience' => $notifiable->experience,
                        'user' => $notifiable->user,
                        'url' =>  $this->getAutoLoginUrl($notifiable)
                    ]
                );
        } else {
            exit;
        }
    }

    public function toTwilio($notifiable)
    {
        $message = "Te hemos asignado una nueva experiencia de formación. ¿Listo para comenzar?";
        $message .= "\n{$this->getAutoLoginUrl($notifiable)}";

        $from = "+19165426722";

        if ($notifiable->user->contact_preference == "sms") {
            $from = "+19165426722";
        }

        if ($notifiable->user->contact_preference == "whatsapp") {
            $from = 'whatsapp:+19165426722';
        }

        Log::info('Twilio Whastapp Message: '.$message);

        return (new TwilioSmsMessage())
            ->content($message)->from($from);
    }

    public function toSns($notifiable)
    {
        $message = "Te hemos asignado una nueva experiencia de formación. ¿Listo para comenzar?";
        $message .= "\n{$this->getAutoLoginUrl($notifiable)}";

        $from = "+19165426722";

        if ($notifiable->contact_preference == "sms") {
            $from = "+19165426722";
        }

        return SnsMessage::create()
            ->body($message)
            ->transactional()
            ->sender('apithy');
    }

    private function getUrl($notifiable, $path)
    {
        $url = route('experience.preview', [$notifiable->experience]);

        $https = env('APP_HTTPS', false);

        if ($notifiable->user->company()->count()) {
            $company = $notifiable->user->company[0];

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
            $url .= "apithy.com/".$path;
        }

        return $url;
    }

    public function getAutoLoginUrl($notifiable)
    {
        $autoLoginService = new AutoLoginService();
        $autoLogin = $autoLoginService->createAutoLogin(
            $notifiable->user,
            "experiences/{$notifiable->experience->system_id}/preview"
        );

        return $this->getUrl($notifiable, "autologin/{$autoLogin->token}");
    }
}
