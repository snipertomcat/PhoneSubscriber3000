<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20/12/18
 * Time: 07:34 PM
 */

namespace Apithy\Channels;

use Aloha\Twilio\Twilio;
use Illuminate\Notifications\Notification;

class TwilioChannel
{
    /**
     * The Twilio instance.
     *
     * @var \Aloha\Twilio\Twilio;
     */
    protected $twilio;
    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('twilio')) {
            return;
        }

        $message = $notification->toTwilio($notifiable);
    
        $configKey = config('twilio.twilio.default');
        $config = config('twilio.twilio.connections.' . $configKey);
        $this->from = $message->from ?: $config['from'];
        $this->twilio = new Twilio($config['sid'], $config['token'], $this->from);
        $this->twilio->message($to, trim($message->content));
    }
}
