<?php

namespace Apithy\Listeners;

use Apithy\Events\InvitationCreated;
use Apithy\Invitation;
use Apithy\Jobs\InvitationReminder;
use Apithy\Mail\Invitation as InvitationMail;
use Apithy\Mail\InvitationEnroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendInvitationEmail
{
    /**
     * Handle the event.
     *
     * @param  InvitationCreated $event
     * @return void
     */
    public function handle(InvitationCreated $event)
    {
        switch ($event->invitation->type) {
            case Invitation::TYPE_ENROLL:
                $email = explode('_', $event->invitation->contact)[0];
                $mail = Mail::to($email);
                $mail->send(new InvitationEnroll(
                    $event->invitation,
                    $event->experience,
                    $event->user
                ));
                break;
            default:
                $mail = Mail::to($event->invitation->contact);
                $mail->queue(new InvitationMail($event->invitation));
                break;
        }

        $company = $event->invitation->company;
        if ($company->settings) {
            $company_settings = $company->settings->where('module', '=', 'invitations');
            $company_settings = $company_settings->mapWithKeys(function ($item) {
                return [$item->setting => $item->value];
            });

            $remainder_limit = (isset($company_settings['remainder_limit']))
                ? $company_settings['remainder_limit'] : 3;
            $remainder_period = (isset($company_settings['remainder_period']))
                ? $company_settings['remainder_period'] : 12;
            $jobIds = [];

            $x = 1;

            while ($x <= $remainder_limit) {
                $job = (new InvitationReminder($event->invitation))
                    ->delay(Carbon::now()->addDays($x)->setHour(8)->setMinutes(30)->setSeconds(0));
                $jobIds[] = dispatch($job);
                $x++;
            }
        }
    }
}
