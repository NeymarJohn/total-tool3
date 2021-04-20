<?php

namespace App\Http\Requests\SuperAdmin\Stripe;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class UpdateRequest extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'paypal_client_id' => 'required_if:paypal_status,on',
            'paypal_secret' => 'required_if:paypal_status,on',
            'api_key' => 'required_if:stripe_status,on',
            'api_secret' => 'required_if:stripe_status,on',
            'webhook_key' => 'required_if:stripe_status,on',
            'razorpay_key' => 'required_if:razorpay_status,on',
            'razorpay_secret' => 'required_if:razorpay_status,on',
            'razorpay_webhook_secret' => 'required_if:razorpay_status,on',
            'paystack_client_id' => 'required_if:paystack_status,on',
            'paystack_secret' => 'required_if:paystack_status,on',
            'paystack_merchant_email' => 'required_if:paystack_status,on',
            'mollie_api_key' => 'required_if:mollie_status,on',
            'authorize_api_login_id' => 'required_if:authorize_status,on',
            'authorize_transaction_key' => 'required_if:authorize_status,on',
            'authorize_signature_key' => 'required_if:authorize_status,on',
            'authorize_environment' => 'in:sandbox,live',
        ];
    }
}
