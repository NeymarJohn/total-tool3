<?php

namespace App\Http\Requests\SuperAdmin\FeatureSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class UpdateRequest extends SuperAdminBaseRequest
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
        if($this->has('icon') ){
            $rules['icon'] = "required";
        }
        return $rules;
    }
}
