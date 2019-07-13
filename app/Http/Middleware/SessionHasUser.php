<?php

namespace arsatapi\Http\Middleware;

use Closure;

class SessionHasUser
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
         if(!(session()->has("user"))) {
        return $next($request);
    }
    return $next($request);
    }
}
