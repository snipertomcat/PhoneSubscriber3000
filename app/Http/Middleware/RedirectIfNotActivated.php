<?php

namespace Apithy\Http\Middleware;

use Apithy\User;
use Closure;

class RedirectIfNotActivated
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

        if ($user && $user->status == User::STATUS_ACTIVE) {
            return $next($request);
        }
        if (auth()->check()) {
            auth()->logout();
        }
        return redirect('/login');
    }
}
