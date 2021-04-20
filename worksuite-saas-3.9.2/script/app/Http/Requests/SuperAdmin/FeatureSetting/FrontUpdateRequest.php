<?php

namespace App\Http\Requests\SuperAdmin\FeatureSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class FrontUpdateRequest extends SuperAdminBaseRequest
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
            "language" => "required"
        ];
        return $rules;
    }
}
