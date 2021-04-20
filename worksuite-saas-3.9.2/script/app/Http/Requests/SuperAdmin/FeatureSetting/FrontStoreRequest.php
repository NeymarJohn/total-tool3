<?php

namespace App\Http\Requests\SuperAdmin\FeatureSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class FrontStoreRequest extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            "title" => "required",
            "description" => "required",
        ];
        return $rules;
    }
}
