<?php

namespace Apithy\Mail;

use Apithy\Experience;
use Apithy\Invitation;
use Apithy\Services\AutoLoginService;
use Apithy\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvitationEnroll extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $invitation;
    public $experience;
    public $user;
    public $login_link;

    /**
     * Create a new message instance.
     *
     * @param Model $invitation
     * @param Experience $experience
     * @param User $user
     */
    public function __construct(Invitation $invitation, Experience $experience, User $user)
    {
        $this->invitation = $invitation;
        $this->experience = $experience;
        $this->user = $user;
        $this->login_link = $this->autoLogin(
            $user,
            "experiences/{$experience->system_id}/enroll/{$invitation->assignation_id}"
        );
    }

    private function autoLogin(User $user, $url)
    {
        $autoLoginService = new AutoLoginService();
        $url = url($url);
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
        return $this->markdown('mails.experiences.invitation');
    }
}
