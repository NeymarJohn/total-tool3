<?php

namespace App\Http\Controllers;

use App\ClientPayment;
use App\Invoice;
use App\Payment;
use App\PaymentGatewayCredentials;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function verifyStripeWebhook(Request $request)
    {
        $payload = @file_get_contents("php://input");
        $sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
        $event = null;
        $invoiceId = null;

        $payloadData = json_decode($request->getContent(), true);

//        return response('Webhook Handled', 200);
        $stripeCredentials = PaymentGatewayCredentials::first();

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = $stripeCredentials->stripe_webhook_secret;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return response('Invalid Payload', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('Invalid signature', 400);
        }

        $payload = $payloadData;

        $eventId = $payload['id'];
        $eventCount = ClientPayment::where('event_id', $eventId)->count();
        /*\Log::debug(['hello3',$payload]);*/
        if($payload['data']['object']['object'] == 'invoice') {
            // Do something with $event
            if ($payload['type'] == 'invoice.payment_succeeded' && $eventCount == 0) {
                $planId = $payload['data']['object']['lines']['data'][0]['plan']['id'];
                $customerId = $payload['data']['object']['customer'];
                $amount = $payload['data']['object']['lines']['data'][0]['amount'];
                $transactionId = $payload['data']['object']['lines']['data'][0]['id'];
                $invoiceId = $payload['data']['object']['lines']['data'][0]['plan']['metadata']['invoice_id'];

                $previousClientPayment = ClientPayment::where('plan_id', $planId)
                    ->where('transaction_id', $transactionId)
                    ->whereNull('event_id')
                    ->first();
                if ($previousClientPayment) {
                    $previousClientPayment->event_id = $eventId;
                    $previousClientPayment->save();
                } else {
                    $invoice = Invoice::find($invoiceId);
                    $paymentData = Payment::where('invoice_id', $invoiceId)->where('gateway', 'Stripe')->first();

                    if($paymentData){
                        $payment = $paymentData;
                        $payment->event_id = $eventId;
                        $payment->paid_on = Carbon::now();
                        $payment->status = 'complete';
                        $payment->save();
                    }
                    else{
                        $payment = new Payment();
                        $payment->currency_id = $invoice->currency_id;
                        $payment->invoice_id = $invoice->id;
                        $payment->amount = $amount / 100;
                        $payment->event_id = $eventId;
                        $payment->gateway = 'Stripe';
                        $payment->paid_on = Carbon::now();
                        $payment->status = 'complete';
                        $payment->save();
                    }

                }
            }
        }
        // If webhook with payment_intent (Success or Failed)
        elseif($payload['data']['object']['object'] == 'payment_intent'){
            if ($payload['type'] == 'payment_intent.succeeded')
            {
                $planId = null;
                if(isset($payload['data']['object']['lines']['data'][0]['plan']['id'])){
                    $planId = $payload['data']['object']['lines']['data'][0]['plan']['id'];
                }
                $amount = $payload['data']['object']['amount'];
                $transactionId = $payload['data']['object']['id'];
                $invoiceId = $payload['data']['object']['metadata']['invoice_id'];

                $previousClientPayment = ClientPayment::where('plan_id', $planId)
                    ->where('transaction_id', $transactionId)
                    ->whereNull('event_id')
                    ->first();
                if ($previousClientPayment) {
                    $previousClientPayment->event_id = $eventId;
                    $previousClientPayment->save();
                } else {
                    $invoice = Invoice::find($invoiceId);

                    $payment = new Payment();
                    $payment->currency_id = $invoice->currency_id;
                    $payment->invoice_id = $invoice->id;
                    $payment->amount = $amount / 100;
                    $payment->event_id = $eventId;
                    $payment->transaction_id = $transactionId;
                    $payment->gateway = 'Stripe';
                    $payment->paid_on = Carbon::now();
                    $payment->status = 'complete';
                    $payment->save();

                    return response('Webhook Handled', 200);
                }

                return response('Webhook Handled', 200);
            }
        }

        return response('Webhook Handled', 200);
    }
}
