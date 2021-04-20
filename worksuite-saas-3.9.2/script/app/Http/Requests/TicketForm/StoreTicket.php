<?php

namespace App\Http\Requests\TicketForm;

use App\Http\Requests\CoreRequest;

class StoreTicket extends CoreRequest
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
        $setting = \App\Company::findOrFail($this->company_id);
        $global = \App\GlobalSetting::first();

        $rules = [
            'email'                 => 'required|email',
            'name'                  => 'required',
            'ticket_subject'        => 'required',
            'message'               => 'required',
        ];

        if($global->google_captcha_version == "v2" && $setting->ticket_form_google_captcha){
            $rules['g-recaptcha-response'] = 'required';
        }

        if($global->google_captcha_version == "v3" && $setting->ticket_form_google_captcha){
            $rules['recaptcha_token'] ='required';
        }

        return $rules;
    }
}
