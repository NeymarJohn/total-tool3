<?php

namespace App\Http\Requests\Lead;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends CoreRequest
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
        if($this->has('company_id')){
            $setting = \App\Company::findOrFail($this->company_id);
        }
        else{
            $setting = company();
        }

        $global = \App\GlobalSetting::first();

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:leads,client_email',
        ];

        if($setting)
        {
            if($global->google_captcha_version == "v2" && $setting->lead_form_google_captcha){
                $rules['g-recaptcha-response'] = 'required';
            }

            if($global->google_captcha_version == "v3" && $setting->lead_form_google_captcha){
                $rules['recaptcha_token'] ='required';
            }
        }

        return $rules;
    }
}
