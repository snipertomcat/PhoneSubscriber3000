<?php

namespace Apithy\Http\Middleware;

use Closure;

class ForceLogoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            auth()->logout();
        }
        return $next($request);
    }
}
