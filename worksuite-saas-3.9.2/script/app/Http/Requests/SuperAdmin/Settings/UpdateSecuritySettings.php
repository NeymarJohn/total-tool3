<?php

namespace App\Http\Requests\SuperAdmin\Settings;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class UpdateSecuritySettings extends SuperAdminBaseRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function authorize()
    {
        return true;
    }
    

    public function rules()
    {
        $rules = [];
        if ($this->has('google_recaptcha_status')) {
            $rules = [
                'version' => 'required_if:google_recaptcha_status,active',
                'google_recaptcha_key_v2' => 'required_if:version,v2',
                'google_recaptcha_secret_v2' => 'required_if:version,v2',
                'google_recaptcha_key_v3' => 'required_if:version,v3',
                'google_recaptcha_secret_v3' => 'required_if:version,v3',
            ];
        }
        return $rules;
    }
    
    
}
