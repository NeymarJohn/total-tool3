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
        $setting = \App\Setting::with('currency')->first();

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:leads,client_email|unique:users,email',
        ];

        if($setting->lead_form_google_captcha){
            $rules['g-recaptcha-response'] = 'required';
        }

        return $rules;
    }
}
