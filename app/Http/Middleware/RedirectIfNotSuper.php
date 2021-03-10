<?php

namespace Apithy\Http\Middleware;

use Closure;

class RedirectIfNotSuper
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

        $user = $request->user();

        if ($user && $user->isSuper()) {
            return $next($request);
        }

         return redirect('/');
    }
}
