<?php

namespace App\Http\Requests\Events;

use App\Rules\CheckAfterDate;
use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreEvent extends CoreRequest
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
        $startDateTime = $this->start_date.' '.$this->start_time;
        $endDateTime = $this->end_date.' '.$this->end_time;
        return [
            'event_name' => 'required',
            'start_date' => 'required',
            'end_date' => ['required' , new CheckDateFormat(null,company()->date_format) , new CheckEqualAfterDate('start_date',company()->date_format)],
            'start_time' => 'required',
            'end_time' => ['required',new CheckAfterDate('',company()->date_format.' '.company()->time_format, $startDateTime, null, $endDateTime)],
            'all_employees' => 'sometimes',
            'user_id.0' => 'required_unless:all_employees,true',
            'where' => 'required',
            'description' => 'required',
        ];
    }

    public function messages() {
        return [
            'user_id.0.required_unless' => __('messages.atleastOneValidation'),
            'end_time.after' => __('messages.endTimeGreaterThenStart')
        ];
    }
}
