<?php

namespace App\Http\Requests\SuperAdmin\FeatureSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class StoreRequest extends SuperAdminBaseRequest
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
        if($this->has('icon') ){
            $rules['icon'] = "required";
        }
        else{
            $rules['image'] = "required";
        }
        return $rules;
    }
}
