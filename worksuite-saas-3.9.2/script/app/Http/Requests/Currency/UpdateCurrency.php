<?php

namespace App\Http\Requests\Currency;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrency extends CoreRequest
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

        $user = user();

        return [
            'currency_name' => 'required|unique:currencies,currency_name,'.$this->route('currency').',id,company_id,'.$user->company_id,
            'currency_symbol' => 'required|unique:currencies,currency_symbol,'.$this->route('currency').',id,company_id,'.$user->company_id,
            'usd_price' => 'required_if:is_cryptocurrency,yes',
            'currency_code' => 'required|unique:currencies,currency_code,'.$this->route('currency').',id,company_id,'.$user->company_id,
        ];
    }
}
