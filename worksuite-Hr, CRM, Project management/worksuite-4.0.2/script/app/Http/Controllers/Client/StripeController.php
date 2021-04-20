<?php

namespace App\Http\Controllers\Client;

use App\ClientPayment;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Invoice;
use App\Payment;
use App\PaymentGatewayCredentials;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Subscription;
use Validator;
use URL;
use Session;
use Redirect;

use Stripe\Charge;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $stripeCredentials = PaymentGatewayCredentials::first();

        /** setup Stripe credentials **/
        Stripe::setApiKey($stripeCredentials->stripe_secret);
        $this->pageTitle = 'Stripe';
    }

    /**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paymentWithStripe(Request $request, $invoiceId)
    {
        $redirectRoute = 'client.invoices.show';
        $id = $invoiceId;

        return $this->makeStripePayment($request, $invoiceId, $redirectRoute, $id);
    }

    public function paymentWithStripePublic(Request $request, $invoiceId)
    {
        $redirectRoute = 'front.invoice';
        $id = md5($invoiceId);

        return $this->makeStripePayment($request, $invoiceId, $redirectRoute, $id);
    }

    private function makeStripePayment($request, $invoiceId, $redirectRoute, $id)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $invoice->status = 'paid';
        $invoice->save();

        \Session::put('success', 'Payment success');
        return Reply::redirect(route($redirectRoute, $id), 'Payment success');
    }
}
