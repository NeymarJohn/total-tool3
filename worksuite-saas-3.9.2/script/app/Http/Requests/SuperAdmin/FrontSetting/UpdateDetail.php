<?php

namespace App\Http\Requests\SuperAdmin\FrontSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class UpdateDetail extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "header_title" => "required",
            "header_description" => "required",
            "feature_title" => "sometimes|required",
            "price_title" => "sometimes|required",
            "price_description" => "sometimes|required",
        ];
    }
}
