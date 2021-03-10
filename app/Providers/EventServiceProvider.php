<?php

namespace Apithy\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

/**
 * Class EventServiceProvider
 * @package Apithy\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'Apithy\Listeners\SetUserCompany',
        ],
        /*
        'Illuminate\Auth\Events\Registered' => [
            'Apithy\Listeners\SendRegisteredEmail',
        ],
        */
        'BeyondCode\EmailConfirmation\Events\Confirmed' => [
            'Apithy\Listeners\SendWelcomeEmail'
        ],
        'Apithy\Events\InvitationCreated' => [
            'Apithy\Listeners\SendInvitationEmail',
        ],
        'Apithy\Events\InvitationResend' => [
            'Apithy\Listeners\ResendInvitationEmail',
        ],
        'Apithy\Events\ExperienceEnrolled' => [
            'Apithy\Listeners\StudentExperienceEnrolled'
        ],
        'Apithy\Events\ExperienceEnrollUpdate' => [
            'Apithy\Listeners\StudentExperienceEnrollUpdate'
        ],
        'Apithy\Events\UserPayment' => [
            'Apithy\Listeners\UserPaymentInvoice'
        ],
        'Apithy\Events\LicenseAssignation' => [
            'Apithy\Listeners\LicenseExpiration',
            'Apithy\Listeners\UserLicenceAssigned'
        ],
        'Apithy\Events\LicenseRemoved' => [
            'Apithy\Listeners\LicenseRemovedEmail'
        ],
        'Apithy\Events\InvitationCreatedFromPhone' => [
            'Apithy\Listeners\SendInvitationPhone'
        ],
        'Apithy\Events\InvitationResendFromPhone' => [
            'Apithy\Listeners\ResendInvitationPhone'
        ],
        'Apithy\Events\EnrollmentFinished' => [
            'Apithy\Listeners\SendEnrollmentCertificate'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
