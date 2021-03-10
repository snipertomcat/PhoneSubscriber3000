<?php

namespace Apithy\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'profile/avatar',
        'companies/*/logo',
        'companies/*/company-cover',
        'experiences/*',
        'student/progress',
        'sessions/register-activity',
        'demoFlyer',
        'activity/set-score',
        'experience-session/cache',
        'experience-session/cache/*',
        'question',
        'question/*',
        'evaluation',
        'evaluation/*',
        'answer',
        'answer/*',
        'user-answer',
        'login',
        'set-password/*'
    ];
}
