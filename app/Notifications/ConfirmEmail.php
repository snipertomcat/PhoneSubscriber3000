<?php

namespace Apithy\Notifications;

use Apithy\Utilities\Master\Master;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmEmail extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $data = [
            'title' => ucfirst($notifiable->name),
            'body' => __(
                'confirmation::confirmation.confirmation_body',
                ['business_name' => $notifiable->company[0]->name]
            ),
            'button_label' => __('confirmation::confirmation.confirmation_button'),
            'body_bottom' => __('confirmation::confirmation.confirmation_body_botton'),
            'body_closure' => Master::getAngleMessage(),
            'confirm_url' => url("register/confirm/$notifiable->confirmation_code")
        ];

        return (new MailMessage)
            ->subject(__('confirmation::confirmation.confirmation_subject'))
            ->view("mails.html.confirmation", $data);
    }
}
