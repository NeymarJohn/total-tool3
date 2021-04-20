<?php

namespace App\Http\Requests\Events;

use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEvent extends CoreRequest
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
            'event_name' => 'required',
            'start_date' => 'required',
            'end_date' => ['required', new CheckDateFormat(null,$setting->date_format), new CheckEqualAfterDate('start_date',$setting->date_format)],
            'all_employees' => 'sometimes',
            'where' => 'required',
            'description' => 'required',
        ];
    }
}
