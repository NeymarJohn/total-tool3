<?php

namespace App\Http\Middleware;

use App\Company;
use App\Package;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Redirect;

class LicenseExpireDateWise
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
        $user = user();

        $company = company_setting();
        $expireOn = $company->licence_expire_on;
        $currentDate = Carbon::now();
        $package = Package::where('id', $company->package_id)->first();

        if ((!is_null($expireOn) && $expireOn->lessThan($currentDate)) || ($company->status == 'license_expired'  && $package->default != 'yes')){
            return Redirect::route('admin.billing');
        }
        return $next($request);
    }
}
