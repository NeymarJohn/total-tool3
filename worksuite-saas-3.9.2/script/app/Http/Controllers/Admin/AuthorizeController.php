<?php

namespace App\Http\Controllers\Admin;

use App\AuthorizeSubscription;
use App\Company;
use App\Helper\Reply;
use App\Http\Requests\AuthorizePaymentRequest;
use App\Notifications\CompanyUpdatedPlan;
use App\Package;
use App\PaystackInvoice;
use App\StripeSetting;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

//use Unicodeveloper\Paystack\Paystack;
class AuthorizeController extends Controller
{
    function createSubscription(AuthorizePaymentRequest $request)
    {
        $credential = StripeSetting::first();
        $package = Package::find($request->plan_id);

        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($credential->authorize_api_login_id);
        $merchantAuthentication->setTransactionKey($credential->authorize_transaction_key);

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($package->name . " " . $request->type . " Subscription");

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();

        $packageType = $request->type;

        if ($request->type == 'annual') {
            $interval->setLength(365);
        } else {
            $interval->setLength(30);
        }

        $interval->setUnit("days");

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new \DateTime(Carbon::now()->format('Y-m-d')));
        $paymentSchedule->setTotalOccurrences("24");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($package->{$request->type . '_price'});
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($request->card_number);
        $creditCard->setExpirationDate($request->expiration_year . '-' . $request->expiration_month);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);
        $subscription->setPayment($payment);

        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("1234354");
        $order->setDescription("Description of the subscription");
        $subscription->setOrder($order);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($request->name);
        $billTo->setLastName($request->name);

        $subscription->setBillTo($billTo);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);


        if ($credential->authorize_environment == 'sandbox') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $subscription = AuthorizeSubscription::where('company_id', company()->id)->first();

            if ($subscription) {
                $subscription->subscription_id = $response->getSubscriptionId();
            } else {
                $subscription = new AuthorizeSubscription();
            }

            $subscription->company_id = company()->id;
            $subscription->subscription_id = $response->getSubscriptionId();
            $subscription->plan_id = $package->id;
            $subscription->plan_type = $packageType;

            $subscription->save();

            return Reply::success('Successfully subscribed. Please wait...');
        } else {
            return Reply::error($response ? $response->getMessages()->getMessage()[0]->getText() : 'Something went wrong!');
        }
    }

    public function checkSubscription(Request $request)
    {
        session()->forget('company');
        session()->forget('company_setting');

        $company = company();

        if ($company->package_id == $request->package_id && $company->package_type == $request->type) {
            return Reply::dataOnly(['status' => 'success', 'webhook' => true]);
        }

        return Reply::dataOnly(['status' => 'success', 'webhook' => false]);
    }
}
