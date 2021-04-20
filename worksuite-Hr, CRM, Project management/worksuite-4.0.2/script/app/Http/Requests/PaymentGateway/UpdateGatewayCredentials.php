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
            'razorpay_key' => 'required_if:razorpay_status,on',
            'razorpay_secret' => 'required_if:razorpay_status,on',
            'paypal_mode' => 'required_if:paypal_status,on|in:sandbox,live'
        ];
    }
}
