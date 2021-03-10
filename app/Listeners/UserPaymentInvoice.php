<?php

namespace Apithy\Listeners;

use Apithy\Events\UserPayment;
use Apithy\Mail\Invoice;
use Illuminate\Support\Facades\Mail;

class UserPaymentInvoice
{
    /**
     * Handle
     *
     * @param ExperienceEnrolled $event
     * @return void
     */
    public function handle(UserPayment $event)
    {
        Mail::to($event->user->email)
            ->queue(new Invoice($event->transaction, $event->user, $event->use_for));
    }
}
