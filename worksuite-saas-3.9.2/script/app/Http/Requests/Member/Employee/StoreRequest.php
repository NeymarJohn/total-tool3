<?php

namespace App\Http\Requests\Member\Employee;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends CoreRequest
{
    /**Member
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
            "employee_id" => "required|unique:employee_details",
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
            'slack_username' => 'nullable|unique:employee_details,slack_username',
            'hourly_rate' => 'nullable|numeric',
            'department' => 'required',
            'designation' => 'required',
        ];
    }
}
