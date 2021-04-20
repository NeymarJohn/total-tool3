<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\PaymentGatewayCredentials;
use App\Http\Requests\PaymentGateway\UpdateGatewayCredentials;

class PaymentGatewayCredentialController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.paymentGatewayCredential';
        $this->pageIcon = 'ti-key';
    }

    public function index(){
        $this->credentials = PaymentGatewayCredentials::first();
        return view('admin.payment-gateway-credentials.edit', $this->data);
    }

    public function update(UpdateGatewayCredentials $request, $id) {
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
        ($request->razorpay_status) ? $credential->razorpay_status = 'active' : $credential->razorpay_status = 'inactive';

        $credential->save();

        return Reply::success(__('messages.settingsUpdated'));
    }
}
