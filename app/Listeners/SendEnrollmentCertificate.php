<?php

namespace Apithy\Listeners;

use Apithy\Events\InvitationCreated;
use Apithy\Mail\UserRegister as RegisterMail;
use Apithy\Notifications\EnrollmentCertificate;
use Apithy\SmsVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendEnrollmentCertificate
{
    /**
     * Handle the event.
     *
     * @param  InvitationCreated $event
     * @return void
     */
    public $enrollment;

    public function handle($event)
    {
        $this->enrollment = $event->enrollment;
        $this->sendCertificate($this->enrollment);
    }

    protected function sendCertificate($enrollment)
    {

        $sent_certificates_config = $enrollment->user->company[0]->settings('enrollment', 'send_enrollment_finished_certificates', 1)->first();
        $min_score_certificates = $enrollment->user->company[0]->settings('enrollment', 'min_enrollment_score')->first();

        if ($min_score_certificates) {
            $min_score = $min_score_certificates->value;
        } else {
            $min_score = .10;
        }

        if ($sent_certificates_config &&
            $enrollment->score >= $min_score
            && !$enrollment->certificate_sent) {
            if (!$enrollment->certificate_sent) {
                $notification = app(\Apithy\Notifications\EnrollmentCertificate::class);
                $enrollment->notify($notification);
            }
        }
    }
}
