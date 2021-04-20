<?php

namespace App\Http\Requests\Settings;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganisationSettings extends CoreRequest
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
            "company_name" => "required",
            "company_email" => "required|email",
            "company_phone" => "required",
            "address" => "required"
        ];

        if($this->has('google_recaptcha') && $this->google_recaptcha == 'on')
        {
            $rules['google_recaptcha_key'] = 'required';
            $rules['google_recaptcha_secret'] = 'required';
        }

        return $rules;
    }
}
