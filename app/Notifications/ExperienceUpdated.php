<?php

namespace Apithy\Notifications;

use Apithy\Utilities\Master\Master;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class ExperienceUpdated extends Notification implements ShouldQueue
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

        if ($notifiable->user->contact_preference == "sms" || $notifiable->user->contact_preference == "whatsapp") {
            return getSMSChannel();
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
                ->subject('¡Experiencia actualizada!')
                ->view(
                    "mails.html.licence_assigned",
                    [
                        'experience' => $notifiable->experience,
                        'user' => $notifiable->user,
                        'url' => $this->getUrl($notifiable)
                    ]
                );
        } else {
            exit;
        }
    }

    public function toTwilio($notifiable)
    {
        $message = "En {$notifiable->user->company[0]->name} creemos en ti, ";
        $message .= "por eso, te hemos asignado nuevas experiencias en apithy aNGLE.";
        $message .= "\n¡Comienza ya! \n{$this->getUrl($notifiable)}";

        $from = "+19165426722";

        if ($notifiable->contact_preference == "sms") {
            $from = "+19165426722";
        }

        if ($notifiable->contact_preference == "whatsapp") {
            $from = 'whatsapp:+19165426722';
        }

        return (new TwilioSmsMessage())
            ->content($message)->from($from);
    }

    private function getUrl($notifiable)
    {
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
            $url .= "apithy.com/experiences/{$notifiable->experience->system_id}/view/".$notifiable->id;
        }

        return $url;
    }
}
