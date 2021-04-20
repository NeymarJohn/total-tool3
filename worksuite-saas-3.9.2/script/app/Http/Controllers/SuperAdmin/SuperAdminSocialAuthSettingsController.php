<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\SocialAuth\UpdateRequest;
use App\PaypalInvoice;
use App\SocialAuthSetting;
use App\StripeSetting;
use App\Subscription;
use App\Traits\StripeSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;


class SuperAdminSocialAuthSettingsController extends SuperAdminBaseController
{
    use StripeSettings;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.socialLogin';
        $this->pageIcon = 'icon-settings';
    }

    public function index()
    {
        $this->credentials = SocialAuthSetting::first();
        return view('super-admin.social-login-settings.index', $this->data);
    }

    public function update(UpdateRequest $request)
    {
        $socialAuth = SocialAuthSetting::first();

        $socialAuth->twitter_client_id = $request->twitter_client_id;
        $socialAuth->twitter_secret_id = $request->twitter_secret_id;
        ($request->twitter_status) ? $socialAuth->twitter_status = 'enable' : $socialAuth->twitter_status = 'disable';

        $socialAuth->facebook_client_id = $request->facebook_client_id;
        $socialAuth->facebook_secret_id = $request->facebook_secret_id;
        ($request->facebook_status) ? $socialAuth->facebook_status = 'enable' : $socialAuth->facebook_status = 'disable';

        $socialAuth->linkedin_client_id = $request->linkedin_client_id;
        $socialAuth->linkedin_secret_id = $request->linkedin_secret_id;
        ($request->linkedin_status) ? $socialAuth->linkedin_status = 'enable' : $socialAuth->linkedin_status = 'disable';

        $socialAuth->google_client_id = $request->google_client_id;
        $socialAuth->google_secret_id = $request->google_secret_id;
        ($request->google_status) ? $socialAuth->google_status = 'enable' : $socialAuth->google_status = 'disable';

        $socialAuth->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

    public function changePaymentMethod(UpdateRequest $request)
    {

        $stripe = StripeSetting::first();
        $type = $request->type;
        $bothUncheck = $request->bothUncheck;

        // Stripe unsubscribe
        if ($type == 'stripe') {
            $this->setStripConfigs();
            $subscriptions = Subscription::with('company')
                ->whereNull('ends_at')->get();

            if (sizeof($subscriptions) > 0) {
                foreach ($subscriptions as $subscription) {
                    $company = $subscription->company;
                    $company->subscription('main')->cancel();
                }
            }
        }

        // Paypal unsubscribe
        if ($type == 'paypal') {
            $credential = StripeSetting::first();
            $paypal_conf = Config::get('paypal');
            $api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
            $api_context->setConfig($paypal_conf['settings']);

            $paypalInvoice = PaypalInvoice::with('company')
                ->whereNotNull('transaction_id')
                ->whereNull('end_on')
                ->where('status', 'paid')->get();

            if (sizeof($paypalInvoice) > 0) {
                foreach ($paypalInvoice as $inv) {
                    $agreementId = $inv->transaction_id;
                    $agreement = new Agreement();

                    $agreement->setId($agreementId);
                    $agreementStateDescriptor = new AgreementStateDescriptor();
                    $agreementStateDescriptor->setNote("Cancel the agreement");

                    try {
                        $agreement->cancel($agreementStateDescriptor, $api_context);
                        $cancelAgreementDetails = Agreement::get($agreement->getId(), $api_context);

                        // Set subscription end date
                        $inv->end_on = Carbon::parse($cancelAgreementDetails->agreement_details->final_payment_date)->format('Y-m-d H:i:s');
                        $inv->save();
                    } catch (Exception $ex) {
                    }
                }
            }
        }

        // Save Active Status
        $stripe->stripe_status = ($type === 'stripe') && $bothUncheck == 'false' ? 'active' : 'inactive';
        $stripe->paypal_status = ($type === 'paypal') && $bothUncheck == 'false' ? 'active' : 'inactive';
        $stripe->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

    public function offlinePayment(Request $request)
    {
    }
}
