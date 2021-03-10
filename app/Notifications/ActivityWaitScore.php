<?php

namespace Apithy\Notifications;

use Apithy\Services\AutoLoginService;
use Apithy\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\AwsSns\SnsMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class ActivityWaitScore extends Notification implements ShouldQueue
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
        $user = $notifiable->getAdmin();
        if (!$user->contact_preference) {
            return ['mail'];
        }

        if ($user->contact_preference == "sms") {
            return getSMSChannel();
        }

        if ($user->contact_preference == "whatsapp") {
                return [TwilioChannel::class];
        }

        return [$user->contact_preference];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    public function toMail($notifiable)
    {
        $user = $notifiable->getAdmin();
        $scoreLink = $this->autoLogin($notifiable, $user);
        return (new MailMessage)
            ->view("mails.activity_wait_score", [
                'owner' => $user->full_name,
                'score_form_link' => $scoreLink,
                'experience' => $notifiable->progress->session->experience->title,
                'actividad' => $notifiable->activity->title,
                'session' => $notifiable->progress->session->title,
                'student' => $notifiable->progress->enrollment->user->full_name,
                'url' => $scoreLink
            ])
            ->subject('Actividad en espera de calificacion');
    }

    public function toTwilio($notifiable)
    {
        $user = $notifiable->getAdmin();
        $url = $this->autoLogin($notifiable, $user);
        $msj = "{$notifiable->progress->enrollment->user->full_name} ha enviado una respuesta para el reto";
        $msj .= "{$notifiable->progress->session->experience->title}";
        $msj .= "\n más info en: {$url}";

        $from = "+19165426722";

        if ($user->contact_preference == "sms") {
            $from = "+19165426722";
        }

        if ($user->contact_preference == "whatsapp") {
            $from = 'whatsapp:+19165426722';
        }

        return (new TwilioSmsMessage())
            ->content($msj)->from($from);
    }

    public function toSns($notifiable)
    {
        $user = $notifiable->getAdmin();
        $url = $this->autoLogin($notifiable, $user);
        $msj = "{$notifiable->progress->enrollment->user->full_name} ha enviado una respuesta para el reto";
        $msj .= "{$notifiable->progress->session->experience->title}";
        $msj .= "\n más info en: {$url}";

        $from = "+19165426722";

        if ($user->contact_preference == "sms") {
            $from = "+19165426722";
        }

        return SnsMessage::create()
            ->body($msj)
            ->promotional()
            ->sender('apithy');
    }


    private function autoLogin($notifiable, $user)
    {
        $autoLoginService = new AutoLoginService();
        $url = $this->getUrl($notifiable, $this->getXapiUrl($notifiable));
        $autoLogin = $autoLoginService->createAutoLogin($user, $url);
        return $this->getUrl($notifiable, "autologin/{$autoLogin->token}");
    }

    private function getXapiUrl($notifiable)
    {
        $ai = "activity_id={$notifiable->activity_id}";
        $epi = "enrollment_progress_id={$notifiable->enrollment_progress_id}";
        return "activity-evaluation?{$ai}&{$epi}";
    }

    private function getUrl($notifiable, $path)
    {
        $url = url($path);

        $https = env('APP_HTTPS', false);

        if ($notifiable->progress->enrollment->user->company()->count()) {
            $company = $notifiable->progress->enrollment->user->company[0];
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
