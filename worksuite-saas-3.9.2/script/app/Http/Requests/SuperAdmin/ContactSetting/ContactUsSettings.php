<?php

namespace App\Http\Requests\SuperAdmin\ContactSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class ContactUsSettings extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "address" => "required",
            "phone" => "required",
            "email" => "required | email",
        ];
    }
}
