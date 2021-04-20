<?php

namespace App\Http\Requests\Admin\Contract;

use App\Rules\CheckDateFormat;
use Froiden\LaravelInstaller\Request\CoreRequest;

class RenewRequest extends CoreRequest
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
        $setting = global_setting();
        return [
            'amount_1' => 'required',
            'start_date_1' => ['required' , new CheckDateFormat(null,$setting->date_format)],
            'end_date_1' => ['required' , new CheckDateFormat(null,$setting->date_format)],
        ];
    }

    public function messages()
    {
        return [
            'amount_1.required' => 'The amount field is required.',
            'start_date_1.required' => 'The start date field is required.',
            'end_date_1.required' => 'The end date field is required.'
        ];
    }
}
