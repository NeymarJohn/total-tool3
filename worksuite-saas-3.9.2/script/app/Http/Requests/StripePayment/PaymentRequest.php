<?php

namespace App\Http\Requests\StripePayment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'payment_method' => 'required',
            'plan_id' => 'required|exists:packages,id',
            'stripeEmail' => 'required|email|exists:companies,company_email'
        ];
    }
}
