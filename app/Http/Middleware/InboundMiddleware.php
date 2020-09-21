<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class InboundMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = 'inbound')
    {
         if (!Auth::guard($guard)->check()) {
            return redirect('inbound/login');
         }
         return $next($request);
    }
}
