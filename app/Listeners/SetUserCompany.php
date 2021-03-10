<?php

namespace Apithy\Listeners;

use Apithy\Company;
use Apithy\Events\InvitationCreated;
use Illuminate\Auth\Events\Login;

class SetUserCompany
{
    /**
     * Handle the event.
     *
     * @param  InvitationCreated  $event
     * @return void
     */

    public function handle(Login $event)
    {
        $user=$event->user;
        $company=null;

        if ($user->hasCompany()) {
            $company=$user->company()->first();
        } else {
            $company=new Company();
        }

        session(['apithy.company'=>$company]) ;
    }
}
