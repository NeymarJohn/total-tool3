<?php

namespace App\Http\Requests\User;

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
        return [
            'email' => 'required|unique:users,email,'.$this->route('employee'),
            'slack_username' => 'nullable|unique:employee_details,slack_username,'.$this->route('employee'),
            'name'  => 'required',
        ];
    }
}
