<?php

namespace App\Http\Requests\AttendanceSetting;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceSetting extends CoreRequest
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
        $rules = [
            'office_start_time' => 'required',
            'office_end_time' => 'required',
            'late_mark_duration' => 'required',
        ];

        if($this->has('ip_check')) {
            $rules['ip.*'] = 'ip';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'ip.*' => __('validation.ip', ['attribute' => 'ip'])
        ];
    }
}
