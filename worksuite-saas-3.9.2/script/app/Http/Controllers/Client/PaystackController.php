<?php

namespace App\Http\Controllers\Client;

use App\ClientPayment;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\PaymentGatewayCredentials;
use App\Scopes\CompanyScope;
use App\Traits\PaystackSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Unicodeveloper\Paystack\Paystack;

class PaystackController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'Paystack';
    }

    use PaystackSettings;
    protected $client;
    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway(Request $request, $invoiceId)
    {
        $invoice = Invoice::find($invoiceId);

        $credential = PaymentGatewayCredentials::withoutGlobalScope(CompanyScope::class)
            ->where('company_id', $invoice->company_id)
            ->first();
        $key       = ($credential->paystack_client_id)? $credential->paystack_client_id : env('PAYSTACK_PUBLIC_KEY');
        $apiSecret = ($credential->paystack_secret)? $credential->paystack_secret : env('PAYSTACK_SECRET_KEY');
        $email = ($credential->paystack_merchant_email)? $credential->paystack_merchant_email : env('MERCHANT_EMAIL');
        $url = ($credential->paystack_payment_url)? $credential->paystack_payment_url : env('PAYSTACK_PAYMENT_URL');

        Config::set('paystack.publicKey', $key);
        Config::set('paystack.secretKey', $apiSecret);
        Config::set('paystack.paymentUrl', $url);
        Config::set('paystack.merchantEmail', $email);

        $paystack = new Paystack();
        $request->first_name = (user() ? user()->name : $request->name);

        $request->email = (user() ? user()->email : $request->email);
        $request->orderID = '1';
        $request->amount = $invoice->total*100;
        $request->quantity = '1';
        $request->reference = $paystack->genTranxRef();
        $request->key = config('paystack.secretKey');

        session([
            'invoice_id' => $invoiceId,
        ]);

        return $paystack->getAuthorizationUrl()->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        $invoice = Invoice::find(\session()->get('invoice_id'));
        $credential = PaymentGatewayCredentials::withoutGlobalScope(CompanyScope::class)
            ->where('company_id', $invoice->company_id)
            ->first();

        $key       = ($credential->paystack_client_id)? $credential->paystack_client_id : env('PAYSTACK_PUBLIC_KEY');
        $apiSecret = ($credential->paystack_secret)? $credential->paystack_secret : env('PAYSTACK_SECRET_KEY');
        $email = ($credential->paystack_merchant_email)? $credential->paystack_merchant_email : env('MERCHANT_EMAIL');
        $url = ($credential->paystack_payment_url)? $credential->paystack_payment_url : env('PAYSTACK_PAYMENT_URL');

        Config::set('paystack.publicKey', $key);
        Config::set('paystack.secretKey', $apiSecret);
        Config::set('paystack.paymentUrl', $url);
        Config::set('paystack.merchantEmail', $email);

        $paystack  = new Paystack();
        $paymentDetails = $paystack->getPaymentData();

        if($paymentDetails['status']) {

            $invoice->status = 'paid';
            $invoice->save();
            // Save details in database and redirect to paypal
            $clientPayment = new ClientPayment();
            $clientPayment->currency_id = $invoice->currency_id;
            $clientPayment->amount = $invoice->total;

            $clientPayment->transaction_id = $paymentDetails['data']['reference'];
            $clientPayment->gateway = 'Paystack';
            $clientPayment->status = 'complete';

            $clientPayment->company_id = $invoice->company_id;
            $clientPayment->invoice_id = $invoice->id;
            $clientPayment->project_id = $invoice->project_id;
            $clientPayment->save();
        }

        return redirect(route('client.invoices.show', session()->get('invoice_id')));
    }
}
