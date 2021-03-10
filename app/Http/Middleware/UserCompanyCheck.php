<?php

namespace Apithy\Http\Middleware;

use Apithy\User;
use Closure;

/**
 * Class RedirectIfBanned
 * @package Apithy\Http\Middleware
 */
class UserCompanyCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);

        $access = $request->get('access');
        $company = $request->session()->get('current.company');

        if ($company != 'apithy-account_name' && $company != 'www') {
            $user = User::CompanyIn($company->id)->where('access', '=', $access)->get();

            if (!count($user)) {
                $request->session()->put(['current.company' => false]);
                return redirect('/login')
                    ->withErrors(['Usuario no existente en esta empresa']);
            }
        }
        return $next($request);
    }
}
