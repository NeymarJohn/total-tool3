<?php

namespace App\Http\Controllers\Client;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Payment;
use App\PaymentGatewayCredentials;
use Carbon\Carbon;
use Razorpay\Api\Api;

class RazorPayController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'Razorpay';
    }

    public function payWithRazorPay()
    {
        $credential = PaymentGatewayCredentials::first();

        $paymentId = request('paymentId');

        $apiKey    = $credential->razorpay_key;
        $secretKey = $credential->razorpay_secret;

        $api        = new Api($apiKey, $secretKey);
        $payment    = $api->payment->fetch($paymentId); // Returns a particular payment

        $purchaseId = $payment->notes->purchase_id;

        $invoice = Invoice::findOrFail($purchaseId);
        $amount   = $invoice->total * 100; // Convert in paise

        // If transaction successfully done
        if ($amount == $payment->amount && $payment->status == 'authorized') {
            $payment->capture(array('amount' => $payment->amount, 'currency' => $invoice->currency->currency_code));

            $invoice->status = 'paid';
            $invoice->save();

            $payment = new Payment();
            $payment->project_id = $invoice->project_id;
            $payment->currency_id = $invoice->currency_id;
            $payment->amount = $invoice->total;
            $payment->gateway = 'Razorpay';
            $payment->transaction_id = $paymentId;
            if (!is_null($invoice->project_id)) {
                $payment->project_id = $invoice->project_id;
            }
            $payment->paid_on = Carbon::now();
            $payment->status = 'complete';
            $payment->save();

            \Session::put('success','Payment success');

            if (!auth()->check()) {
                $redirectRoute = 'front.invoice';
                $id = md5($invoice->id);
                return Reply::redirect(route($redirectRoute, $id), 'Payment success');
            }
            return Reply::redirect(route('client.invoices.show', $invoice->id), 'Payment success');
        }

        return Reply::error('Transaction Failed');

    }
}
