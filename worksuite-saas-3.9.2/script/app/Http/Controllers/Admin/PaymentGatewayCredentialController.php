<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\PaymentGateway\UpdateGatewayCredentials;
use App\PaymentGatewayCredentials;
use Illuminate\Http\Request;

class PaymentGatewayCredentialController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.paymentGatewayCredential';
        $this->pageIcon = 'ti-key';
    }

    public function index()
    {
        $this->credentials = PaymentGatewayCredentials::first();
        return view('admin.payment-gateway-credentials.edit', $this->data);
    }

    public function update(UpdateGatewayCredentials $request, $id)
    {
        $credential = PaymentGatewayCredentials::findOrFail($id);
        $credential->paypal_client_id = $request->paypal_client_id;
        $credential->paypal_secret = $request->paypal_secret;
        $credential->paypal_mode = $request->paypal_mode;
        ($request->paypal_status) ? $credential->paypal_status = 'active' : $credential->paypal_status = 'deactive';

        $credential->stripe_client_id = $request->stripe_client_id;
        $credential->stripe_secret = $request->stripe_secret;
        $credential->stripe_webhook_secret = $request->stripe_webhook_secret;
        ($request->stripe_status) ? $credential->stripe_status = 'active' : $credential->stripe_status = 'deactive';

        $credential->razorpay_key = $request->razorpay_key;
        $credential->razorpay_secret = $request->razorpay_secret;
        $credential->razorpay_webhook_secret = $request->razorpay_webhook_secret;
        ($request->razorpay_status) ? $credential->razorpay_status = 'active' : $credential->razorpay_status = 'deactive';

        $credential->paystack_client_id = $request->paystack_client_id;
        $credential->paystack_secret = $request->paystack_secret;
        $credential->paystack_merchant_email = $request->paystack_merchant_email;
        ($request->paystack_status) ? $credential->paystack_status = 'active' : $credential->paystack_status = 'inactive';

        $credential->mollie_api_key = $request->mollie_api_key;
        ($request->mollie_status) ? $credential->mollie_status = 'active' : $credential->mollie_status = 'inactive';

        $credential->authorize_api_login_id = $request->authorize_api_login_id;
        $credential->authorize_transaction_key = $request->authorize_transaction_key;
        $credential->authorize_environment = $request->authorize_environment;
        ($request->authorize_status) ? $credential->authorize_status = 'active' : $credential->authorize_status = 'inactive';

        $credential->save();

        return Reply::success(__('messages.settingsUpdated'));
    }
}
