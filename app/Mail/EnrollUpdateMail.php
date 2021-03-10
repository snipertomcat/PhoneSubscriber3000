<?php

namespace Apithy\Mail;

use Apithy\Experience;
use Apithy\Services\AutoLoginService;
use Apithy\User;
use Illuminate\Mail\Mailable;

class EnrollUpdateMail extends Mailable
{
    public $user;
    public $experience;
    public $message;
    public $login_link;

    /**
     * EnrollUpdateMail constructor.
     * @param Experience $experience
     * @param User $user
     */
    public function __construct(Experience $experience, User $user, $message)
    {
        $this->experience = $experience;
        $this->user = $user;
        $this->message = $message;
        $this->login_link = $this->autoLogin($user, "experiences/{$experience->system_id}");
    }

    private function autoLogin(User $user, $url)
    {
        $autoLoginService = new AutoLoginService();
        $url = url($url);
        $autoLogin = $autoLoginService->createAutoLogin($user, $url);
        return $autoLoginService->urlGenerator($autoLogin);
    }

    /**
     * @return EnrollUpdateMail
     */
    public function build()
    {
        return $this
            ->from('webmaster@apithy.com')
            ->markdown('mails.experiences.enroll.updated');
    }
}
