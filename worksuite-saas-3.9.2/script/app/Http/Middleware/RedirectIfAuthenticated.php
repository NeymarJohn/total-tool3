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
        if (Auth::guard($guard)->check()) {

            $user = auth()->user();

            if($user->super_admin == 1) {
                return redirect(route('super-admin.dashboard'));
            }
            if($user->hasRole('admin')){
                return redirect(route('admin.dashboard'));
            }
            if($user->hasRole('employee')){
                return redirect(route('member.dashboard'));
            }
            if($user->hasRole('client')){
                return redirect(route('client.dashboard.index'));
            }
        }
        // when session expire then it reload user to login page

        if ($request->ajax()) {
            return response('Session Expire', 401);
        }

        return $next($request);
    }
}
