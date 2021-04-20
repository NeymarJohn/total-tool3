<?php

namespace App\Http\Requests\TimeLogs;

use App\Company;
use App\Http\Requests\CoreRequest;
use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
use Illuminate\Foundation\Http\FormRequest;

class StoreTimeLog extends CoreRequest
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
        $setting = Company::with('currency', 'package')->withoutGlobalScope('active')->where('id', company()->id)->first();
        $rules =  [
            'start_time' => 'required',
            'end_time' =>  'required',
            'memo' => 'required',
            'task_id' => 'required',
            'user_id' => 'required',
            'end_date' =>[new CheckDateFormat(null, $setting->date_format) , new CheckEqualAfterDate('start_date', $setting->date_format)],

        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'project_id.required' => __('messages.chooseProject')
        ];
    }
}
