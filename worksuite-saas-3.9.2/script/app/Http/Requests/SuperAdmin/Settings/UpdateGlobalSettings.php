<?php

namespace App\Http\Requests\SuperAdmin\Settings;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class UpdateGlobalSettings extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "company_name" => "required",
            "company_email" => "required|email",
//            "company_phone" => "required",
            "address" => "required"
        ];
    }
}
