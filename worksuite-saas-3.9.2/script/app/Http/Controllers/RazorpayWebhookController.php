<?php

namespace App\Http\Controllers;

use App\Company;
use App\Currency;
use App\Notifications\CompanyPurchasedPlan;
use App\Notifications\CompanyUpdatedPlan;
use App\Package;
use App\RazorpayInvoice;
use App\RazorpaySubscription;
use App\StripeInvoice;
use App\StripeSetting;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controller;
use Razorpay\Api\Api;
use Razorpay\Api\Errors;

class RazorpayWebhookController extends Controller
{

    const PAYMENT_AUTHORIZED        = 'subscription.charged';
    const PAYMENT_FAILED            = 'payment.failed';
    const SUBSCRIPTION_CANCELLED    = 'subscription.cancelled';

    public function saveInvoices(Request $request) {
        $credential = StripeSetting::first();

        $apiKey        = $credential->razorpay_key;
        $secretKey     = $credential->razorpay_secret;
        $secretWebhook = $credential->razorpay_webhook_secret;


        $api  = new Api($apiKey, $secretKey);

        $post = file_get_contents('php://input');

        $requestData = json_decode($post, true);

        if (isset($_SERVER['HTTP_X_RAZORPAY_SIGNATURE']) === true)
        {
            $razorpayWebhookSecret = $secretWebhook;

            try
            {
                $api->utility->verifyWebhookSignature($post,
                    $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'],
                    $razorpayWebhookSecret);
            }
            catch (Errors\SignatureVerificationError $e)
            {
                return;
            }
            switch ($requestData['event'])
            {
                case self::PAYMENT_AUTHORIZED:
                    return $this->paymentAuthorized($requestData);
                case self::PAYMENT_FAILED:
                    return $this->paymentFailed($requestData);
                case self::SUBSCRIPTION_CANCELLED:
                    return $this->subscriptionCancelled($requestData);
                default:
                    return;
            }
        }

    }

    /**
     * Does nothing for the main payments flow currently
     * @param array $requestData Webook Data
     */
    protected function paymentFailed(array $requestData)
    {
        return;
    }
    /**
     * Does nothing for the main payments flow currently
     * @param array $requestData Webook Data
     */
    protected function subscriptionCancelled(array $requestData)
    {
        return;
    }

    /**
     * @param array $requestData
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function paymentAuthorized(array $requestData)
    {
        //
        // Order entity should be sent as part of the webhook payload
        //

        $packageId = $requestData['payload']['payment']['entity']['notes']['package_id'];
        $type      = $requestData['payload']['payment']['entity']['notes']['package_type'];
        $companyID = $requestData['payload']['payment']['entity']['notes']['company_id'];

        $plan = Package::find($packageId);
        $company = Company::findOrFail($companyID);

        // If it is already marked as paid, ignore the event
        $razorpayPaymentId = $requestData['payload']['payment']['entity']['id'];
        $credential = StripeSetting::first();

        $apiKey    = $credential->razorpay_key;
        $secretKey = $credential->razorpay_secret;

        $api = new Api($apiKey, $secretKey);

        $payment = $api->payment->fetch($razorpayPaymentId);
            //
            // If the payment is only authorized, we capture it
            // If the merchant has enabled auto capture
            //
            try
            {
                Log::debug('companyName = '.$company->company_name);
                if($company) {

                    $invoiceID      = $requestData['payload']['payment']['entity']['invoice_id'];
                    $orderID        = $requestData['payload']['payment']['entity']['order_id'];
                    $subscriptionID = $requestData['payload']['subscription']['entity']['id'];
                    $customerID     = $requestData['payload']['subscription']['entity']['customer_id'];
                    $amount         = $requestData['payload']['payment']['entity']['amount'];
                    $endTimeStamp   = $requestData['payload']['subscription']['entity']['end_at'];
                    $currencyCode   = $requestData['payload']['payment']['entity']['currency'];
                    $transactionId  = $requestData['account_id'];
                    $endDate        = \Carbon\Carbon::createFromTimestamp($endTimeStamp)->format('Y-m-d');

                    $currency = Currency::where('currency_code', $currencyCode)->first();

                    if ($currency) {
                        $currencyID = $currency->id;
                    }
                    else{
                        $currencyID = Currency::where('currency_code', 'USD')->first()->id;
                    }

                    // Store invoice details
                    $stripeInvoice = new RazorpayInvoice();
                    $stripeInvoice->company_id      = $company->id;
                    $stripeInvoice->currency_id     = $currencyID;
                    $stripeInvoice->order_id        = $orderID;
                    $stripeInvoice->subscription_id = $subscriptionID;
                    $stripeInvoice->invoice_id      = $invoiceID;
                    $stripeInvoice->transaction_id  = $transactionId;
                    $stripeInvoice->amount          = $payment->amount/100;
                    $stripeInvoice->package_id      = $packageId;
                    $stripeInvoice->pay_date        = \Carbon\Carbon::now()->format('Y-m-d');
                    $stripeInvoice->next_pay_date   = $endDate;
                    $stripeInvoice->save();

                    $subscription = RazorpaySubscription::where('subscription_id', $subscriptionID)->first();
                    $subscription->customer_id = $customerID;
                    $subscription->save();

                    // Change company status active after payment
                    $company->status = 'active';
                    $company->save();

                    $generatedBy  = User::whereNull('company_id')->get();
                    $lastInvoice = RazorpayInvoice::first();

                    if($lastInvoice){
                        Notification::send($generatedBy, new CompanyUpdatedPlan($company, $plan->id));
                    }else{
                        Notification::send($generatedBy, new CompanyPurchasedPlan($company, $plan->id));
                    }

                    return response('Webhook Handled', 200);
                }
            }
            catch (\Exception $e)
            {
                //
                // Capture will fail if the payment is already captured
                //
                $log = array(
                    'message'         => $e->getMessage(),
                    'payment_id'      => $razorpayPaymentId,
                    'event'           => $requestData['event']
                );
                error_log(json_encode($log));
            }

        // Graceful exit since payment is now processed.
        exit;
    }
}
