<?php

namespace App\Http\Requests\Admin\Contract;

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
        return [
            'amount_1' => 'required',
            'start_date_1' => 'required|date',
            'end_date_1' => 'required|date',
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
