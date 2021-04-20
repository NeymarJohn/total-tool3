<?php

namespace App\Http\Requests\Member\User;

use App\EmployeeDetails;
use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployee extends CoreRequest
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
        $detailID = EmployeeDetails::where('user_id', $this->route('employee'))->first();
        return [
            'email' => 'required|unique:users,email,'.$this->route('employee'),
            'slack_username' => 'nullable|unique:employee_details,slack_username,'.$detailID->id,
            'name'  => 'required',
            'hourly_rate' => 'numeric',
        ];
    }
}
