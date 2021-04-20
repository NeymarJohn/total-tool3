<?php

namespace App\Http\Requests\Currency;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreCurrency extends CoreRequest
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
            'currency_name' => 'required',
            'currency_symbol' => 'required',
            'usd_price' => 'required_if:is_cryptocurrency,yes',
            'currency_code' => 'required'
        ];
    }
}
