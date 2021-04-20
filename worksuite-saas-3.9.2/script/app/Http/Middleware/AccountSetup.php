<?php

namespace App\Http\Middleware;

use App\InvoiceSetting;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AccountSetup
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
        $company = auth()->user()->company;
        $invoiceSetting = InvoiceSetting::first();
        
        if (!$company->company_name || !$company->company_email ||  !$company->address || !$invoiceSetting->invoice_prefix || !$invoiceSetting->estimate_prefix || !$invoiceSetting->credit_note_prefix || !$invoiceSetting->template || is_null($invoiceSetting->due_after) || !$invoiceSetting->invoice_terms){
            return Redirect::route('admin.account-setup.index');
        }
        return $next($request);
    }
}
