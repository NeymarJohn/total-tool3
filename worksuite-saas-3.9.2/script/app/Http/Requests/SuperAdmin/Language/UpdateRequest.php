<?php

namespace App\Http\Requests\SuperAdmin\Language;

use Froiden\LaravelInstaller\Request\CoreRequest;

class UpdateRequest extends CoreRequest
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
            'language_name' => 'required|unique:language_settings,language_name,'.$this->route('id'),
            'language_code'  => 'required|unique:language_settings,language_code,'.$this->route('id'),
            'status'  => 'required',
        ];
    }
}
