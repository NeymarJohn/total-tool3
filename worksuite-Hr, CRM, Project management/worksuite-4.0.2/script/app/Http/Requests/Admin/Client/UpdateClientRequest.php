<?php

namespace App\Http\Requests\Admin\Client;

use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [
            'email' => 'required|unique:users,email,'.$this->route('client'),
            'slack_username' => 'nullable|unique:employee_details,slack_username,'.$this->route('client'),
            'name'  => 'required',
            'website' => 'nullable|url',
            'phone_code' => 'required_with:mobile',
        ];

        if (request()->get('custom_fields_data')) {
            $fields = request()->get('custom_fields_data');
            foreach ($fields as $key => $value) {
                $idarray = explode('_', $key);
                $id = end($idarray);
                $customField = \App\CustomField::findOrFail($id);
                if ($customField->required == "yes" && (is_null($value) || $value == "")) {
                    $rules["custom_fields_data[$key]"] = 'required';
                }
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];
        if (request()->get('custom_fields_data')) {
            $fields = request()->get('custom_fields_data');
            foreach ($fields as $key => $value) {
                $idarray = explode('_', $key);
                $id = end($idarray);
                $customField = \App\CustomField::findOrFail($id);
                if ($customField->required == "yes") {
                    $attributes["custom_fields_data[$key]"] = $customField->label;
                }
            }
        }
        return $attributes;
    }
}
