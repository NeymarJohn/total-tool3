<?php
namespace App\Http\Controllers\Admin;

use App\Company;
use App\Package;
use App\PaypalInvoice;
use App\StripeSetting;
use App\Subscription;
use App\Traits\StripeSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Common\PayPalModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Notification;

/** All Paypal Details class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Carbon\Carbon;
use App\User;
use App\Notifications\CompanyUpdatedPlan;

class RazorpayPaypalController extends AdminBaseController
{
    private $_api_context;
    use StripeSettings;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $credential = StripeSetting::first();

        /** setup PayPal api context **/
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
        $this->_api_context->setConfig($paypal_conf['settings']);
        $this->pageTitle = 'modules.paymentSetting.paypal';
    }

    /**
     * Show the application paywith paypalpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function payWithPaypal()
    {
        return view('paywithpaypal', $this->data);
    }

    /**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paymentWithpaypal(Request $request, $invoiceId, $type)
    {
        $package = Package::where('id', $invoiceId)->first();

        if($type == 'annual'){
            $totalAmount = $package->annual_price;
            $frequency = 'year';
            $cycle = 0;
        }else{
            $totalAmount = $package->monthly_price;
            $frequency = 'month';
            $cycle = 0;
        }
        $this->companyName = company()->company_name;

        $plan = new Plan();
        $plan->setName('#'.$package->name)
            ->setDescription('Payment for package '.$package->name)
            ->setType('INFINITE');

        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Payment for package '.$package->name)
            ->setType('REGULAR')
            ->setFrequency(strtoupper($frequency))
            ->setFrequencyInterval(1)
            ->setCycles($cycle)
            ->setAmount(new Currency(array('value' => $totalAmount, 'currency' => $package->currency->currency_code)));

        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl(route('admin.paypal-recurring')."?success=true&invoice_id=".$invoiceId)
            ->setCancelUrl(route('admin.paypal-recurring')."?success=false&invoice_id=".$invoiceId)
            ->setAutoBillAmount("yes")
            ->setInitialFailAmountAction("CONTINUE")
            ->setMaxFailAttempts("0")
            ->setSetupFee(new Currency(array('value' => $totalAmount, 'currency' => $package->currency->currency_code)));

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        try {
            $output = $plan->create($this->_api_context);

        } catch (Exception $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error','Connection timeout');
                return Redirect::route('admin.billing.packages');
            } else {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Redirect::route('admin.billing.packages');
            }
        }

        try {
            $patch = new Patch();
            $value = new PayPalModel('{
               "state":"ACTIVE"
             }');
            $patch->setOp('replace')
                ->setPath('/')
                ->setValue($value);

            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);
            $output->update($patchRequest, $this->_api_context);
            $newPlan = Plan::get($output->getId(), $this->_api_context);

        } catch (Exception $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error','Connection timeout');
                return Redirect::route('admin.billing.packages');
            } else {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Redirect::route('admin.billing.packages');
            }
        }
        $company = Company::findOrFail(company()->id);


        // Calculating next billing date
        $today = Carbon::now()->addDays(1); //payment will deduct after 1 day

        $startingDate = $today->toIso8601String();


        $agreement = new Agreement();
        $agreement->setName($package->name)
            ->setDescription('Payment for package # '.$package->name)
            ->setStartDate("$startingDate");

        $plan1 = new Plan();
        $plan1->setId($newPlan->getId());
        $agreement->setPlan($plan1);

        // Add Payer
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        // ### Create Agreement
        try {
            // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
            $agreement = $agreement->create($this->_api_context);

            $approvalUrl = $agreement->getApprovalLink();
        } catch (Exception $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error','Connection timeout');
                return Redirect::route('admin.billing.packages');
            } else {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Redirect::route('admin.billing.packages');
            }
        }
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $newPlan->getId());

        $paypalInvoice = new PaypalInvoice();
        $paypalInvoice->company_id = company()->id;
        $paypalInvoice->package_id = $package->id;
        $paypalInvoice->currency_id = $this->global->currency_id;
        $paypalInvoice->total = $totalAmount;
        $paypalInvoice->status = 'pending';
        $paypalInvoice->plan_id = $newPlan->getId();
        $paypalInvoice->billing_frequency = $frequency;
        $paypalInvoice->billing_interval = 1;
        $paypalInvoice->save();

        if(isset($approvalUrl)) {
            /** redirect to paypal **/
            return Redirect::away($approvalUrl);
        }

        \Session::put('error','Unknown error occurred');
        return Redirect::route('admin.billing.packages');

    }

    public function getPaymentStatus(Request $request)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $invoice_id = Session::get('invoice_id');
        $clientPayment =  PaypalInvoice::where('plan_id', $payment_id)->first();
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty($request->PayerID) || empty($request->token)) {
            \Session::put('error','Payment failed');
            return redirect(route('admin.billing.packages'));
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        /** PaymentExecution object includes information necessary **/
        /** to execute a PayPal account payment. **/
        /** The payer_id is added to the request query parameters **/
        /** when the user is redirected from paypal back to your site **/
        $execution = new PaymentExecution();
        $execution->setPayerId(request()->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {

            /** it's all right **/
            /** Here Write your database logic like that insert record or value in database if you want **/
            $clientPayment->paid_on = Carbon::now();
            $clientPayment->status = 'paid';
            $clientPayment->save();

            Session::put('success','Payment success');
            return Redirect::route('admin.billing.packages');
        }
        Session::put('error','Payment failed');

        return Redirect::route('admin.billing.packages');
    }

    public function payWithPaypalRecurrring(Request $requestObject)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $clientPayment =  PaypalInvoice::where('plan_id', $payment_id)->first();
        $company = company();
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');

        if($requestObject->get('success') == true && $requestObject->has('token') && $requestObject->get('success') != "false" )
        {
            $token = $requestObject->get('token');
            $agreement = new Agreement();

            try {
                // ## Execute Agreement
                // Execute the agreement by passing in the token
                $agreement->execute($token, $this->_api_context);


                if($agreement->getState() == 'Active' || $agreement->getState() == 'Pending') {

                    $this->cancelSubscription();
                    // Calculating next billing date
                    $today = Carbon::now();


                    $clientPayment->transaction_id = $agreement->getId();
                    if($agreement->getState() == 'Active') {
                        $clientPayment->status = 'paid';
                    }
                    $clientPayment->paid_on = Carbon::now();
                    $clientPayment->save();

                    $company->package_id = $clientPayment->package_id;
                    $company->package_type = ($clientPayment->billing_frequency == 'year') ? 'annual' : 'monthly';
                    $company->status = 'active';// Set company status active
                    $company->licence_expire_on = null;
                    $company->save();

                    if( $company->package_type == 'monthly') {
                        $today = $today->addMonth();
                    } else {
                        $today = $today->addYear();
                    }

                    $clientPayment->next_pay_date = $today->format('Y-m-d');
                    $clientPayment->save();

                    //send superadmin notification
                    $generatedBy = User::whereNull('company_id')->get();
                    Notification::send($generatedBy, new CompanyUpdatedPlan($company, $clientPayment->package_id));

                    \Session::put('success',__('messages.paymentSuccessfullyDone'));
                    return Redirect::route('admin.billing.packages');
                }

                \Session::put('error','Payment failed');

                return Redirect::route('admin.billing.packages');

            } catch (Exception $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error','Connection timeout');
                    return Redirect::route('admin.billing.packages');
                } else {
                    \Session::put('error','Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
            }

        }
        else if($requestObject->get('fail') == true || $requestObject->get('success') == "false")
        {
            \Session::put('error','Payment failed');

            return Redirect::route('admin.billing.packages');

        }else {
            abort(403);
        }

    }

    public function cancelAgreement()
    {
        $paypalInvoice = PaypalInvoice::whereNotNull('transaction_id')->whereNull('end_on')
                                    ->where('id', company()->id)->first();

        $agreementId = $paypalInvoice->transaction_id;
        $agreement = new Agreement();

        $agreement->setId($agreementId);
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Cancel the agreement");

        try {
            $agreement->cancel($agreementStateDescriptor, $this->_apiContext);
            $cancelAgreementDetails = Agreement::get($agreement->getId(), $this->_apiContext);

            // Set subscription end date
            $paypalInvoice->end_on = Carbon::parse($cancelAgreementDetails->agreement_details->final_payment_date)->format('Y-m-d H:i:s');
            $paypalInvoice->save();
        } catch (Exception $ex) {

        }

    }

    public function cancelSubscription() {
        $company = company();
        $stripe = DB::table("stripe_invoices")
            ->join('packages', 'packages.id', 'stripe_invoices.package_id')
            ->selectRaw('stripe_invoices.id , "Stripe" as method, stripe_invoices.pay_date as paid_on ,stripe_invoices.next_pay_date')
            ->whereNotNull('stripe_invoices.pay_date')
            ->where('stripe_invoices.company_id', company()->id);

        $allInvoices = DB::table("paypal_invoices")
            ->join('packages', 'packages.id', 'paypal_invoices.package_id')
            ->selectRaw('paypal_invoices.id, "Paypal" as method, paypal_invoices.paid_on,paypal_invoices.next_pay_date')
            ->where('paypal_invoices.status', 'paid')
            ->whereNull('paypal_invoices.end_on')
            ->where('paypal_invoices.company_id', company()->id)
            ->union($stripe)
            ->get();

        $firstInvoice = $allInvoices->sortByDesc(function ($temp, $key) {
            return Carbon::parse($temp->paid_on)->getTimestamp();
        })->first();

        if(!is_null($firstInvoice) && $firstInvoice->method == 'Paypal'){
            $credential = StripeSetting::first();
            $paypal_conf = Config::get('paypal');
            $api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
            $api_context->setConfig($paypal_conf['settings']);

            $paypalInvoice = PaypalInvoice::whereNotNull('transaction_id')->whereNull('end_on')
                ->where('company_id', company()->id)->where('status', 'paid')->first();

            if($paypalInvoice){
                $agreementId = $paypalInvoice->transaction_id;
                $agreement = new Agreement();

                $agreement->setId($agreementId);
                $agreementStateDescriptor = new AgreementStateDescriptor();
                $agreementStateDescriptor->setNote("Cancel the agreement");

                try {
                    $agreement->cancel($agreementStateDescriptor, $api_context);
                    $cancelAgreementDetails = Agreement::get($agreement->getId(), $api_context);

                    // Set subscription end date
                    $paypalInvoice->end_on = Carbon::parse($cancelAgreementDetails->agreement_details->final_payment_date)->format('Y-m-d H:i:s');
                    $paypalInvoice->save();

                    $company->licence_expire_on = $paypalInvoice->end_on;
                    $company->save();

                } catch (Exception $ex) {
                   // \Session::put('error','Some error occur, sorry for inconvenient');
                }
            }

        }elseif(!is_null($firstInvoice) && $firstInvoice->method == 'Stripe'){
            $this->setStripConfigs();

            $subscription = Subscription::where('company_id', company()->id)->whereNull('ends_at')->first();
            if($subscription){
                try {
                    $company->subscription('main')->cancel();

                    $company->licence_expire_on = $subscription->ends_at;
                    $company->save();

                } catch (Exception $ex) {
                    //\Session::put('error','Some error occur, sorry for inconvenient');
                }
            }
        }
    }

    public function paypalInvoiceDownload($id)
    {
        //        header('Content-type: application/pdf');
        $this->invoice = PaypalInvoice::with(['company','currency','package'])->findOrFail($id);
//        $this->settings = $this->company;
        //        return view('invoices.'.$this->invoiceSetting->template, $this->data);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('paypal-invoice.invoice-1', $this->data);
        $filename = $this->invoice->paid_on->format($this->global->date_format).'-'.$this->invoice->next_pay_date->format($this->global->date_format);
        //       return $pdf->stream();
        return $pdf->download($filename . '.pdf');
    }

}
