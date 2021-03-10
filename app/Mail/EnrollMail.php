<?php

namespace Apithy\Mail;

use Apithy\Experience;
use Apithy\Services\AutoLoginService;
use Apithy\User;
use Illuminate\Mail\Mailable;

class EnrollMail extends Mailable
{
    public $user;
    public $experience;
    public $login_link;

    public function __construct(Experience $experience, User $user)
    {
        $this->user = $user;
        $this->experience = $experience;
        $this->login_link = $this->autoLogin($user, $experience);
    }

    private function autoLogin(User $user, Experience $experience)
    {
        $autoLoginService = new AutoLoginService();
        $url = url("experiences/{$experience->system_id}");
        $autoLogin = $autoLoginService->createAutoLogin($user, $url);
        return $autoLoginService->urlGenerator($autoLogin);
    }

    public function build()
    {
        return $this
            ->from('webmaster@apithy.com')
            ->markdown('mails.experiences.enrolled');
    }
}
