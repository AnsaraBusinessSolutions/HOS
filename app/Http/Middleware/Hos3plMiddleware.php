<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class Hos3plMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = 'hos3pl')
    {
         if (!Auth::guard($guard)->check()) {
            return redirect('hos3pl/login');
         }
         return $next($request);
    }
}
