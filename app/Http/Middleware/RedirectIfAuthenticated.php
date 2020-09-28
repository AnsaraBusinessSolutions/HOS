<?php

namespace App\Http\Middleware;

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
        if ($guard == "custodian" && Auth::guard($guard)->check()) {
            return redirect('/custodian/home');
        }
        if ($guard == "hos3pl" && Auth::guard($guard)->check()) {
            return redirect('/hos3pl/home');
        }
        if ($guard == "inventory" && Auth::guard($guard)->check()) {
            return redirect('/inventory/home');
        }
        if ($guard == "admin" && Auth::guard($guard)->check()) {
            return redirect('/admin/home');
        }
        if (Auth::guard($guard)->check()) {
            return redirect('/store/home');
        }

        return $next($request);
    }
}
