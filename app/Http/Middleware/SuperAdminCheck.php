<?php

namespace App\Http\Middleware;

use Closure;

class SuperAdminCheck
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
        if (Auth::user() && Auth::user()->roleid == 1) {
            return $next($request);
        }
        return redirect('/');
    }
}
