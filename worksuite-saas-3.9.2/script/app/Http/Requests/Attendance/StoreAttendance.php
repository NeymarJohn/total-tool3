<?php

namespace App\Http\Requests\Attendance;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttendance extends CoreRequest
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
            'clock_in_time' => 'required',
            'clock_in_ip' => 'required|ip',
            'clock_out_ip' => 'ip'
        ];
    }
}
