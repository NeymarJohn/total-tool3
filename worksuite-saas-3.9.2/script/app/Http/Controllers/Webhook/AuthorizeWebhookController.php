<?php

namespace App\Http\Controllers\Webhook;

use App\AuthorizationInvoice;
use App\AuthorizeSubscription;
use App\Company;
use App\Notifications\CompanyUpdatedPlan;
use App\Package;
use App\PaystackSubscription;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AuthorizeWebhookController extends Controller
{

    public function saveInvoices(Request $request)
    {

        if($request->eventType == 'net.authorize.customer.subscription.created') {
            $subscription = AuthorizeSubscription::where('subscription_id', $request->payload['id'])->first();

            $package = Package::find($subscription->plan_id);
            
            $company = Company::findOrFail($subscription->company_id);
            $authorizeInvoices = new AuthorizationInvoice();
            $authorizeInvoices->company_id = $subscription->company_id;
            $authorizeInvoices->package_id = $subscription->plan_id;
            $authorizeInvoices->transaction_id = $request->payload['profile']['customerPaymentProfileId'];
            $authorizeInvoices->amount = $package->{$subscription->plan_type.'_price'};
            $authorizeInvoices->pay_date = Carbon::now()->format('Y-m-d');

            $packageType = $subscription->plan_type;

            if($packageType == 'monthly') {
                $authorizeInvoices->next_pay_date = Carbon::now()->addMonth()->format('Y-m-d');
            } else {
                $authorizeInvoices->next_pay_date = Carbon::now()->addYear()->format('Y-m-d');
            }
            $authorizeInvoices->save();

            $company->package_id = $authorizeInvoices->package_id;
            $company->package_type = ($packageType == 'annual') ? 'annual' : 'monthly';
            $company->status = 'active';
            $company->licence_expire_on = null;
            $company->save();

            //send superadmin notification
//            $generatedBy = User::allSuperAdmin();
//            Notification::send($generatedBy, new CompanyUpdatedPlan($company, $company->package_id));
        }

    }

}
