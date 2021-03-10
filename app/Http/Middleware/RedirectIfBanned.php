<?php

namespace Apithy\Http\Middleware;

use Apithy\User;
use Closure;

/**
 * Class RedirectIfBanned
 * @package Apithy\Http\Middleware
 */
class RedirectIfBanned
{
    protected $except = [
        '/owner-mobile-resctriction',
        '/404'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user !== User::STATUS_SUSPENDED) {
            return $next($request);
        }
        if (auth()->check()) {
            auth()->logout();
        }
        return redirect('/login');
    }
}
