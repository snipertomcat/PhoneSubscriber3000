<?php

namespace Apithy\Mail;

use Apithy\Services\AutoLoginService;
use Apithy\User;
use Apithy\User as Model;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegister extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $login_link;

    /**
     * Create a new message instance.
     *
     * @param Model $invitation
     * @return void
     */
    public function __construct(Model $user)
    {
        $this->user = $user;
        $this->login_link = $this->autoLogin($user);
    }

    private function autoLogin(User $user)
    {
        $autoLoginService = new AutoLoginService();
        $url = url('profile/edit');
        $autoLogin = $autoLoginService->createAutoLogin($user, $url);
        return $autoLoginService->urlGenerator($autoLogin);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('webmaster@apithy.com')
            ->markdown('mails.registered');
    }
}
