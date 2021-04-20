<?php

namespace App\Http\Middleware;

use App\GlobalSetting;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class SuperAdmin
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
        $user = auth()->user();

        $exists = Storage::disk('storage')->exists('down');
        $setting = GlobalSetting::first();

        if($exists && is_null($setting->purchase_code) &&  (strpos(request()->getHost(), '.test') === false) ){
            return Redirect::route('verify-purchase');
        }

        if (!Auth::check() || $user->super_admin == '0'){
            return Redirect::route('login');
        }
        return $next($request);
    }
}
