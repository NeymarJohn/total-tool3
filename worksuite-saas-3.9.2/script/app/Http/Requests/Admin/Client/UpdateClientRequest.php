<?php

namespace App\Http\Requests\Admin\Client;

use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends CoreRequest
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
            'email' => [
                'required',
                Rule::unique('client_details')->where(function($query) {
                    $query->where(['email' => $this->request->get('email'), 'company_id' => company()->id]);
                })->ignore($this->route('client'), 'id')
            ], 
            // 'slack_username' => 'nullable|unique:employee_details,slack_username,'.$this->route('client'),
            'name'  => 'required',
            'website' => 'nullable|url',
        ];
    }
}
