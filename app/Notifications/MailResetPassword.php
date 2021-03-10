<?php

namespace Apithy\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailResetPassword extends Notification
{
    use Queueable;

    /**
     * Password reset token
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data=[
            'title'=>'Hola '.ucfirst($notifiable->name),

            'body'=> 'Usted está recibiendo este correo electrónico porque hemos recibido una solicitud de ' .
                'restablecimiento de contraseña para su cuenta.',

            'button_label'=> 'Restablecer contraseña',

            'body_bottom'=> 'Si no ha solicitado un restablecimiento de contraseña,' .
                ' no es necesario realizar ninguna otra acción.',

            'body_closure'=>"Nos vemos muy pronto",

            'confirm_url' => url(config('app.url') .
                route('password.reset', $this->token, false))
        ];

        return (new MailMessage)
            ->subject(__('Reinicio de contraseña'))
            ->view("mails.html.reset_password", $data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
