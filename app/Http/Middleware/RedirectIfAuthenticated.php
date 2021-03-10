<?php

namespace Apithy\Http\Middleware;

use Apithy\Company;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user=Auth::getUser();

            if ($user->isAdmin()) {
                return redirect('/home');
            }

            if ($user->company[0]->id !== Company::getDefautCompanyId()) {
                return redirect('/my-experiences');
            }

            return redirect('/experiences');
        }

        return $next($request);
    }
}
