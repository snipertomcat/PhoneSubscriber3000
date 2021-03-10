<?php

namespace Apithy\Notifications;

use Apithy\Services\AutoLoginService;
use Apithy\User;
use Apithy\Utilities\Master\Master;
use Illuminate\Bus\Queueable;
use Apithy\SmsVerification;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\AwsSns\SnsMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class EnrollmentCertificate extends Notification implements ShouldQueue
{
    //implements ShouldQueue
    use Queueable;


    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
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
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if(!$notifiable->certificate_sent) {
            $notifiable->certificate_sent = 1;
            $notifiable->certificate_sent_date= Carbon::now();
            $notifiable->save();

            $data = [
                'user' => $notifiable->user,
                'experience' => $notifiable->experience,
                'url' => $this->getAutoLoginUrl($notifiable)
            ];

            return (new MailMessage)
                ->subject("Completaste exitosamente {$notifiable->experience->title} - apithy.com")
                ->view("mails.html.enrollment_certificate", $data);
        }

        return false;
    }

    public function toTwilio($notifiable)
    {
        if(!$notifiable->certificate_sent) {
            $notifiable->certificate_sent = 1;
            $notifiable->certificate_sent_date= Carbon::now();
            $notifiable->save();

            if ($notifiable->user->contact_preference == "sms") {
                $from = "+19165426722";

                $link_url = $this->getAutoLoginUrl($notifiable);

                $msj = "Completaste exitosamente {$notifiable->experience->title}.\nDescarga tu certificado.\n {$link_url}";

                return (new TwilioSmsMessage())
                    ->content($msj)->from($from);
            }
        }
        return false;
    }

    public function toSns($notifiable)
    {
        if(!$notifiable->certificate_sent) {
            $notifiable->certificate_sent = 1;
            $notifiable->certificate_sent_date= Carbon::now();
            $notifiable->save();

            if ($notifiable->user->contact_preference == "sms") {
                $from = "+19165426722";

                $link_url = $this->getAutoLoginUrl($notifiable);

                $msj = "Completaste exitosamente {$notifiable->experience->title}.\nDescarga tu certificado.\n {$link_url}";

                return SnsMessage::create()
                    ->body($msj)
                    ->transactional()
                    ->sender('apithy');
            }
        }

        return false;
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

    public function getAutoLoginUrl($notifiable)
    {
        $path="experiences/{$notifiable->experience->system_id}/certificate/{$notifiable->id}";

        if ($notifiable->user->contact_preference == "sms") {
            $path="experiences/{$notifiable->experience->system_id}/certificate/{$notifiable->id}?format=image";
        }

        $autoLoginService = new AutoLoginService();
        $autoLogin = $autoLoginService->createAutoLogin(
            $notifiable->user,
            "experiences/{$notifiable->experience->system_id}/certificate/{$notifiable->id}"
        );

        return $this->getUrl($notifiable->user, "autologin/{$autoLogin->token}");
    }
}
