<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Http\Controllers\Controller;
use App\MollieInvoice;
use App\MollieSubscription;
use App\Notifications\CompanyUpdatedPlan;
use App\Package;
use App\Traits\MollieSettings;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Mollie\Laravel\Facades\Mollie;

//use Unicodeveloper\Paystack\Paystack;
class MollieController extends Controller
{
    use MollieSettings;
    protected $client;
    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway(Request $request)
    {


        $this->setMollieConfigs();


        $mollie = MollieSubscription::where('company_id', company()->id)->first();

        $customer = Mollie::api()->customers()->create([
            'name'  => $request->name,
            'email' => $request->mollieEmail,
        ]);

        if ($mollie) {
            $mollie->customer_id = $customer->id;
            $mollie->save();
        } else {
            $mollie = new MollieSubscription();
            $mollie->company_id = company()->id;
            $mollie->customer_id = $customer->id;
            $mollie->save();
        }

        $package = Package::find($request->plan_id);

        try {
            $payment = Mollie::api()->payments()->create([
                'amount' => [
                    'currency' => $package->currency->currency_code,
                    'value'    => $package->{$request->type . '_price'}, // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                'customerId'   => $customer->id,
                'sequenceType' => 'first',
                'description'  => $package->name . ' payment',
                'redirectUrl'  => route('admin.payments.mollie.callback'),
                'webhookUrl'  => route('admin.payments.mollie.callback')
            ]);
        } catch (\Mollie\Api\Exceptions\ApiException $e) {
            if ($e->getField() == 'webhookUrl' && $e->getCode() == '422') {
                dd('Mollie Webhook will work on live server or you can try ngrok. It will not work on localhost', $e->getMessage());
            }
        }

        session([
            'package_id' => $package->id,
            'package_type' => $request->type,
            'paymentId' => $payment->id,
            'package_amount' => $package->{$request->type . '_price'},
            'payment_id' => $payment->id,
        ]);




        // Redirect the user to Mollie's payment screen.
        return redirect($payment->getCheckoutUrl(), 303);
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback(Request $request)
    {
        $this->setMollieConfigs();

        $mollie = MollieSubscription::where('company_id', company()->id)->first();

        $package = Package::find(Session::get('package_id'));
        $customer = Mollie::api()->customers()->get($mollie->customer_id);

        $payment = Mollie::api()->payments()->get(Session::get('paymentId'));

        if ($payment->status == 'paid') {
            $subscription = Mollie::api()->subscriptions()->createFor($customer, [
                'amount' => [
                    'currency' => $package->currency->currency_code,
                    'value'    => $package->{Session::get('package_type') . '_price'}, // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                'interval' => '12 month',
                'description'  => company()->company_name . ' subscribed'
            ]);

            $mollie->subscription_id = $subscription->id;

            $mollie->save();

            $company = Company::findOrFail(company()->id);
            $molliekInvoice = new MollieInvoice();
            $molliekInvoice->company_id = company()->id;
            $molliekInvoice->package_id = Session::get('package_id');
            $molliekInvoice->package_type = Session::get('package_type');
            $molliekInvoice->transaction_id = Session::get('payment_id');
            $molliekInvoice->amount = Session::get('package_amount');
            $molliekInvoice->pay_date = Carbon::now()->format('Y-m-d');

            $packageType = Session::get('package_type');

            if ($packageType == 'monthly') {
                $molliekInvoice->next_pay_date = Carbon::now()->addMonth()->format('Y-m-d');
            } else {
                $molliekInvoice->next_pay_date = Carbon::now()->addYear()->format('Y-m-d');
            }

            $molliekInvoice->save();

            $company->package_id = $molliekInvoice->package_id;
            $company->package_type = ($packageType == 'annual') ? 'annual' : 'monthly';
            $company->status = 'active';
            $company->licence_expire_on = null;
            $company->save();

            //send superadmin notification
            $generatedBy = User::allSuperAdmin();
            Notification::send($generatedBy, new CompanyUpdatedPlan($company, $company->package_id));
            return redirect(route('admin.billing.packages'));
        }

        return redirect(route('admin.billing.packages'))->with('error', 'Your payment failed.');
    }
}
