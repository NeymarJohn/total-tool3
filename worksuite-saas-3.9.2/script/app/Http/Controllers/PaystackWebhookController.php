<?php

namespace App\Http\Controllers;

use App\PaystackSubscription;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class PaystackWebhookController extends Controller
{

    public function saveInvoices(Request $request)
    {
        Log::debug($request->all());

        switch ($request['event']) {
            case "subscription.create":
                $user = User::where('email', $request['data']['customer']['email'])->first();

                $subscription = PaystackSubscription::where('company_id', $user->company_id)->where('customer_id', $request['data']['customer']['customer_code'])->first();
                if($subscription) {
                    $subscription->subscription_id = $request['data']['subscription_code'];
                    $subscription->token = $request['data']['email_token'];
                    $subscription->plan_id = $request['data']['plan']['plan_code'];
                    $subscription->status = 'active';
                } else {
                    $subscription = new PaystackSubscription();
                    $subscription->company_id = $user->company_id;
                    $subscription->subscription_id = $request['data']['subscription_code'];
                    $subscription->token = $request['data']['email_token'];
                    $subscription->customer_id = $request['data']['customer']['customer_code'];
                    $subscription->plan_id = $request['data']['plan']['plan_code'];
                }
                $subscription->save();
                break;
            case "subscription.disable":
                $user = User::where('email', $request['data']['customer']['email'])->first();
                $subscription = PaystackSubscription::where('company_id', $user->company_id)->where('subscription_id', $request['data']['subscription_code'])->first();
                if($subscription) {
                    $subscription->status = 'inactive';
                    $subscription->save();
                }
                break;
            default:
                echo "Wrong event";
        }

    }

    private function getSubscriptionDetails($subscriptionCode)
    {
        $authBearer = 'Bearer '. config('paystack.secretKey');

        $this->client = new Client(
            [
                'base_uri' => Config::get('paystack.paymentUrl'),
                'headers' => [
                    'Authorization' => $authBearer,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json'
                ]
            ]
        );

        $response = $this->client->{'get'}(
            Config::get('paystack.paymentUrl').'/subscription/'.$subscriptionCode
        );

        return $response;
    }
}
