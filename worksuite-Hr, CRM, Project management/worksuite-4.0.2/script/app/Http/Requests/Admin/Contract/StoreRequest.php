<?php

namespace App\Http\Requests\Admin\Contract;

use App\Http\Requests\CoreRequest;
use App\Rules\CheckDateFormat;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends CoreRequest
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
            'client' => 'required',
            'subject' => 'required',
            'amount' => 'required',
            // 'alternate_address' => 'required',
            'contract_type' => 'required|exists:contract_types,id',
            'start_date' => ['required' , new CheckDateFormat(null,$setting->date_format)],
           // 'end_date' => 'required|date_format:"' . $setting->date_format . '"',
        ];
    }
}
