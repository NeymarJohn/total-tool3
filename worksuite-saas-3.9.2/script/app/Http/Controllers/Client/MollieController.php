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
use Illuminate\Support\Facades\Session;
use Mollie\Laravel\Facades\Mollie;
use Unicodeveloper\Paystack\Paystack;

class MollieController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'Mollie';
    }
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
        $key       = ($credential->mollie_api_key)? $credential->mollie_api_key : env('MOLLIE_API_KEY');

        Config::set('mollie.key', $key);

        $customer = Mollie::api()->customers()->create([
            'name'  => (user() ? user()->name : $request->mollieName),
            'email' => (user() ? user()->email : $request->mollieEmail),
        ]);

        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => $invoice->currency->currency_code,
                'value'    => sprintf('%0.2f', $invoice->total), // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'customerId'   => $customer->id,
            'sequenceType' => 'first',
            'description'  => $invoice->invoice_number.' payment',
            'redirectUrl'  => route('client.mollie.callback'),
        ]);

        session([
            'invoice_id' => $invoiceId,
            'paymentId' => $payment->id,
        ]);

        // Redirect the user to Mollie's payment screen.
        return redirect($payment->getCheckoutUrl(), 303);
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        set_time_limit(0);

        $invoice = Invoice::find(\session()->get('invoice_id'));

        $credential = PaymentGatewayCredentials::withoutGlobalScope(CompanyScope::class)
            ->where('company_id', $invoice->company_id)
            ->first();
        $key       = ($credential->mollie_api_key)? $credential->mollie_api_key : env('MOLLIE_API_KEY');

        Config::set('mollie.key', $key);

        $payment = Session::get('paymentId');

        $payment = Mollie::api()->payments()->get($payment);

        if($payment->status == 'paid') {

            $invoice->status = 'paid';
            $invoice->save();
            // Save details in database and redirect to paypal
            $clientPayment = new ClientPayment();
            $clientPayment->currency_id = $invoice->currency_id;
            $clientPayment->amount = $invoice->total;

            $clientPayment->transaction_id = $payment->id;
            $clientPayment->gateway = 'Mollie';
            $clientPayment->status = 'complete';

            $clientPayment->company_id = $invoice->company_id;
            $clientPayment->invoice_id = $invoice->id;
            $clientPayment->project_id = $invoice->project_id;
            $clientPayment->save();
        }

        return redirect(route('client.invoices.show', session()->get('invoice_id')));
    }
}
