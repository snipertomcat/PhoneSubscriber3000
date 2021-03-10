<?php

namespace Apithy\Http\Middleware;

use Apithy\Company;
use Closure;

/**
 * Class RedirectIfBanned
 * @package Apithy\Http\Middleware
 */
class CompanyCheck
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
        //return $next($request);
        $env = env('APP_ENV', 'prod');
        if ($env != 'prod') {
            $env = $env . '.';
        } else {
            $env = 'www.';
        }

        $https = env('APP_HTTPS', false);
        $protocol_part = 'http://';

        if ($https) {
            $protocol_part = 'http://';
        }

        if ($request->get('error_company_check')) {
            return redirect($protocol_part . $env. 'apithy.com')
                ->withErrors(['La empresa a la que tratas de acceder no existe']);
            ;
        }

        $pieces = explode('.', $request->getHost());

        $company_name = $pieces[0];

        if ($company_name =="www" || $company_name == env('APP_ENV')) {
            return $next($request);
        }

        //We try to get the company name value from the sesion
        if (!$company_name) {
            $company = $request->session()->get('current.company');
            if ($company instanceof Company) {
                $company_name = $company->account_name;
            }
        }

        if ($company_name) {
            if ($company_name != 'wwww' && $company_name != 'apithy-account_name') {
                $company = Company::whereAccountName($company_name)->first();

                if (!$company) {
                    //Redirect to main site with errors
                    return redirect($protocol_part . $env . 'apithy.com?error_company_check=true')
                        ->withErrors(['La empresa '.$company_name.' no existe']);
                    ;
                } else {
                    $request->session()->put(['current.company' => $company]);

                    if ($request->getPathInfo()=="/") {
                        return redirect('/login');
                    }
                }
            }
        } else {
            //We set a string value as company for the main site
            return redirect($protocol_part . $env . 'apithy.com?error_company_check=true')
                ->withErrors(['La empresa '.$company_name.' no existe']);
            ;
        }

        return $next($request);
    }
}
