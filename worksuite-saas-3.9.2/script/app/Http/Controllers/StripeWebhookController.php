<?php

namespace App\Http\Controllers;

use App\ClientPayment;
use App\Company;
use App\Invoice;
use App\Notifications\CompanyPurchasedPlan;
use App\Notifications\CompanyUpdatedPlan;
use App\Payment;
use App\PaymentGatewayCredentials;
use App\Scopes\CompanyScope;
use App\StripeInvoice;
use App\Subscription;
use App\Traits\StripeSettings;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controller;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    use StripeSettings;

    public function verifyStripeWebhook(Request $request)
    {
        $payload = @file_get_contents("php://input");
        $sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
        $event = null;
        $invoiceId = null;

        $payloadData = json_decode($request->getContent(), true);

        $stripeCredentials = PaymentGatewayCredentials::first();

        if($payloadData['data']['object']['object'] == 'invoice' && isset($payloadData['data']['object']['lines']['data'][0]['plan']['metadata']['invoice_id'])) {
            $invoiceId = $payloadData['data']['object']['lines']['data'][0]['plan']['metadata']['invoice_id'];
        }
        else if (isset($payloadData['data']['object']['metadata']['invoice_id'])){
            $invoiceId = $payloadData['data']['object']['metadata']['invoice_id'];
        }


//        return response('Webhook Handled', 200);

        if($invoiceId){
            $invoice = Invoice::withoutGlobalScopes([CompanyScope::class])->find($invoiceId);
            if($invoice){
                $stripeCredentials = PaymentGatewayCredentials::where('company_id', $invoice->company_id)->first();
            }
        }

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
//        \Log::debug(['hello3',$payload]);
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
//                                                    ->where('customer_id', $customerId)
                    ->whereNull('event_id')
                    ->first();
                if ($previousClientPayment) {
                    $previousClientPayment->event_id = $eventId;
                    $previousClientPayment->save();
                } else {
                    $invoice = Invoice::find($invoiceId);

                    $paymentData = Payment::where('event_id', $eventId)->first();

                    if($paymentData){
                        $payment = $paymentData;
                    }
                    else{
                        $payment = new Payment();
                    }

                    $payment->project_id = $invoice->project_id;
                    $payment->currency_id = $invoice->currency_id;
                    $payment->amount = $amount / 100;
                    $payment->event_id = $eventId;
                    $payment->gateway = 'Stripe';
                    $payment->paid_on = Carbon::now();
                    $payment->status = 'complete';
                    $payment->save();
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
                        $payment->company_id = $invoice->company_id;
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

    public function saveInvoices(Request $request) {

        $this->setStripConfigs();

        $stripeCredentials = config('cashier.webhook.secret');

        Stripe::setApiKey(config('cashier.secret'));

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = $stripeCredentials;

        $payload = @file_get_contents("php://input");
        $sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
        $event = null;

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

        $payload = json_decode($request->getContent(), true);

        // Do something with $event
        if($payload['data']['object']['object'] == 'invoice') {
            if ($payload['type'] == 'invoice.payment_succeeded') {
                $planId = $payload['data']['object']['lines']['data'][0]['plan']['id'];
                $customerId = $payload['data']['object']['customer'];
                $amount = $payload['data']['object']['amount_paid'];
                $transactionId = $payload['data']['object']['lines']['data'][0]['id'];
//            $invoiceId = $payload['data']['object']['number'];
                $invoiceRealId = $payload['data']['object']['id'];

                $company = Company::where('stripe_id', $customerId)->first();

                $package = \App\Package::where(function ($query) use ($planId) {
                    $query->where('stripe_annual_plan_id', '=', $planId)
                        ->orWhere('stripe_monthly_plan_id', '=', $planId);
                })->first();
                $planType = 'monthly';
                if($package->stripe_annual_plan_id == $planId){
                    $planType = 'annual';
                }

                \Log::debug([$payload]);
//                return response('Webhook Handled', 200);
                if ($company) {
                    // Store invoice details
                    $stripeInvoice = new StripeInvoice();
                    $stripeInvoice->company_id = $company->id;
                    $stripeInvoice->invoice_id = $invoiceRealId;
                    $stripeInvoice->transaction_id = $transactionId;
                    $stripeInvoice->amount = $amount / 100;
                    $stripeInvoice->package_id = $package->id;
                    $stripeInvoice->pay_date = \Carbon\Carbon::now()->format('Y-m-d');
                    $stripeInvoice->next_pay_date = \Carbon\Carbon::createFromTimeStamp($company->upcomingInvoice()->next_payment_attempt)->format('Y-m-d');

                    $stripeInvoice->save();

                    // Change company status active after payment
                    $company->package_id = $package->id;
                    $company->package_type = $planType;

                    // Set company status active
                    $company->licence_expire_on = null;
                    $company->status = 'active';
                    $company->save();
                    \Log::debug([$payload, $package,$company,$planType ]);
                    $generatedBy = User::whereNull('company_id')->get();
                    $lastInvoice = StripeInvoice::where('company_id')->first();

                    if ($lastInvoice) {
                        Notification::send($generatedBy, new CompanyUpdatedPlan($company, $package->id));
                    } else {
                        Notification::send($generatedBy, new CompanyPurchasedPlan($company, $package->id));
                    }

                    return response('Webhook Handled', 200);

                }

                return response('Customer not found', 200);
            } elseif ($payload['type'] == 'invoice.payment_failed') {
                $customerId = $payload['data']['object']['customer'];

                $company = Company::where('stripe_id', $customerId)->first();
                $subscription = Subscription::where('company_id', $company->id)->first();

                if ($subscription && isset($payload['data']['object']['current_period_end'])) {
                    $subscription->ends_at = \Carbon\Carbon::createFromTimeStamp($payload['data']['object']['current_period_end'])->format('Y-m-d');
                    $subscription->save();
                }

                if ($company  && isset($payload['data']['object']['current_period_end'])) {
                    $company->licence_expire_on = \Carbon\Carbon::createFromTimeStamp($payload['data']['object']['current_period_end'])->format('Y-m-d');
                    $company->save();

                    return response('Company subscription canceled', 200);
                }

                return response('Customer not found', 200);
            }
        }
        // If webhook with payment_intent (Success or Failed)
        elseif($payload['data']['object']['object'] == 'payment_intent'){
            if ($payload['type'] == 'payment_intent.succeeded')
            {
                $customerId = $payload['data']['object']['customer'];

                $company = Company::where('stripe_id', $customerId)->first();
                if($company){
                    $subscription = Subscription::where('company_id', $company->id)->latest()->first();

                    if($subscription){
                        \Log::debug([$subscription,$company]);
                        $subscription->stripe_status = 'active';
                        $subscription->save();

                    }
                    return response('Webhook Handled', 200);
                }

                return response('Customer not found', 200);
            }

            elseif ($payload['type'] == 'payment_intent.payment_failed')
            {
                \Log::debug([$payload]);
                $customerId = $payload['data']['object']['customer'];

                $company = Company::where('stripe_id', $customerId)->first();
                if($company)
                {
                    $subscription = Subscription::where('company_id', $company->id)->latest()->first();

                    if($subscription){
                        if( isset($payload['data']['object']['current_period_end'])){
                            $subscription->ends_at = \Carbon\Carbon::createFromTimeStamp($payload['data']['object']['current_period_end'])->format('Y-m-d');
                        }

                        $subscription->save();
                    }

                    if($company) {
                        if( isset($payload['data']['object']['current_period_end'])){
                            $company->licence_expire_on = \Carbon\Carbon::createFromTimeStamp($payload['data']['object']['current_period_end'])->format('Y-m-d');
                        }
                        $company->save();

                        return response('intent failed', 400);
                    }

                }

                return response('Customer not found', 200);
            }
        }
    }
}
