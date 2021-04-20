<?php

namespace App\Http\Requests\SuperAdmin\PriceSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class UpdatePriceSettings extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "price_title" => "required",
            "price_description" => "required",
        ];
    }
}
