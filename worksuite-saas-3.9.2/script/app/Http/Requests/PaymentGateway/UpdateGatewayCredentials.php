<?php

namespace App\Http\Requests\PaymentGateway;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGatewayCredentials extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
            'stripe_client_id' => 'required_if:stripe_status,on',
            'stripe_secret' => 'required_if:stripe_status,on',
            'paypal_mode' => 'required_if:paypal_status,on|in:sandbox,live',
            'razorpay_key' => 'required_if:razorpay_status,on',
            'razorpay_secret' => 'required_if:razorpay_status,on',
            'paystack_client_id' => 'required_if:paystack_status,on',
            'paystack_secret' => 'required_if:paystack_status,on',
            'paystack_merchant_email' => 'required_if:paystack_status,on',
            'mollie_api_key' => 'required_if:mollie_status,on',
            'authorize_api_login_id' => 'required_if:authorize_status,on',
            'authorize_transaction_key' => 'required_if:authorize_status,on',
        ];
    }
}
