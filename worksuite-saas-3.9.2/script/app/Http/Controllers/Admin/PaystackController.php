<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Notifications\CompanyUpdatedPlan;
use App\Package;
use App\PaystackInvoice;
use App\Traits\PaystackSettings;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Unicodeveloper\Paystack\Paystack;

//use Unicodeveloper\Paystack\Paystack;
class PaystackController extends Controller
{
    use PaystackSettings;
    protected $client;
    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway(Request $request)
    {

        $this->setPaystackConfigs();
        $package = Package::find($request->plan_id);
        $paystack = new Paystack();
        $request->first_name = $request->name;
        $request->email = $request->paystackEmail;
        $request->orderID = '1';
        $request->amount = $package->{$request->type.'_price'};
        $request->quantity = '1';
        $request->reference = $paystack->genTranxRef();
        $request->key = config('paystack.secretKey');
        $request->plan = $package->{'paystack_'.$request->type.'_plan_id'};
        $request->currency = 'USD';
        session([
            'package_id' => $package->id,
            'package_type' => $request->type,
            'package_amount' => $package->{$request->type.'_price'},
        ]);

        return $paystack->getAuthorizationUrl()->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        $this->setPaystackConfigs();
        $paystack  = new Paystack();
        $paymentDetails = $paystack->getPaymentData();

        if($paymentDetails['status']) {
            $company = Company::findOrFail(company()->id);
            $paystackInvoices = new PaystackInvoice();
            $paystackInvoices->company_id = company()->id;
            $paystackInvoices->package_id = Session::get('package_id');
            $paystackInvoices->transaction_id = $paymentDetails['data']['reference'];
            $paystackInvoices->amount = Session::get('package_amount');
            $paystackInvoices->pay_date = Carbon::now()->format('Y-m-d');

            $packageType = Session::get('package_type');

            if($packageType == 'monthly') {
                $paystackInvoices->next_pay_date = Carbon::now()->addMonth()->format('Y-m-d');
            } else {
                $paystackInvoices->next_pay_date = Carbon::now()->addYear()->format('Y-m-d');
            }
            $paystackInvoices->save();

            $company->package_id = $paystackInvoices->package_id;
            $company->package_type = ($packageType == 'annual') ? 'annual' : 'monthly';
            $company->status = 'active';
            $company->licence_expire_on = null;
            $company->save();

            //send superadmin notification
            $generatedBy = User::allSuperAdmin();
            Notification::send($generatedBy, new CompanyUpdatedPlan($company, $company->package_id));
        }

        return redirect(route('admin.billing.packages'));
    }
}
