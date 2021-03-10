<?php

namespace Apithy\Providers;

use Apithy\Company;
use Apithy\CompanyArea;
use Apithy\CompanyPosition;
use Apithy\Experience;
use Apithy\Invitation;
use Apithy\Policies\CompanyAreaPolicy;
use Apithy\Policies\CompanyPolicy;
use Apithy\Policies\ExperiencePolicy;
use Apithy\Policies\InvitationPolicy;
use Apithy\Policies\PositionPolicy;
use Apithy\Policies\UserPolicy;
use Apithy\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

/**
 * Class AuthServiceProvider
 * @package Apithy\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Company::class => CompanyPolicy::class,
        Invitation::class => InvitationPolicy::class,
        CompanyArea::class => CompanyAreaPolicy::class,
        CompanyPosition::class => PositionPolicy::class,
        Experience::class => ExperiencePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
