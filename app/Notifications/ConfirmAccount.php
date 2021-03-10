<?php

namespace Apithy\Notifications;

use Apithy\User;
use Apithy\Utilities\Master\Master;
use Illuminate\Bus\Queueable;
use Apithy\SmsVerification;
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\AwsSns\SnsMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmAccount extends Notification implements ShouldQueue
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
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($notifiable->status != User::STATUS_UNCONFIRMED) {
            return;
        }


        $data = [
            'title' => ucfirst($notifiable->name),
            'body' => __(
                'confirmation::confirmation.confirmation_body',
                ['business_name' => $notifiable->company[0]->name]
            ),
            'button_label' => __('confirmation::confirmation.confirmation_button'),
            'body_bottom' => __('confirmation::confirmation.confirmation_body_botton'),
            'body_closure' => Master::getAngleMessage(),
            'confirm_url' => $this->getUrl($notifiable, "register/confirm/$notifiable->confirmation_code")
        ];

        return (new MailMessage)
            ->subject(__('confirmation::confirmation.confirmation_subject'))
            ->view("mails.html.confirmation", $data);
    }

    public function toTwilio($notifiable)
    {

        $code = SmsVerification::where('user_id', $notifiable->id)
            ->where('status', 'pending')
            ->latest()->first();

        if (!$code) {
            return;
        }

        if ($notifiable->contact_preference == "sms") {
            $from = "+19165426722";

            $msj = "Tu código es {$code->code} para verificar tu cuenta en apithy.";
        }

        if ($notifiable->contact_preference == "whatsapp") {
            $from = 'whatsapp:+19165426722';
            $msj = "Tu código es {$code->code} para verificar tu cuenta en apithy.";
        }

        return (new TwilioSmsMessage())
            ->content($msj)->from($from);
    }

    public function toSns($notifiable)
    {
        $code = SmsVerification::where('user_id', $notifiable->id)
            ->where('status', 'pending')
            ->latest()->first();

        if (!$code) {
            return;
        }

        if ($notifiable->contact_preference == "sms") {
            $from = "+19165426722";

            $msj = "Tu código es {$code->code} para verificar tu cuenta en apithy.";
        }

        return SnsMessage::create()
            ->body($msj)
            ->transactional()
            ->sender('apithy');
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
