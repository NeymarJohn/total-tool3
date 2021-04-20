<?php

namespace App\Http\Requests\SuperAdmin\FooterSetting;

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
            "title" => "required|unique:footer_menu,name",
        ];

        if($this->get('content') == 'desc'){
            $rules["description"] = "required";
        }
        else{
            $rules["external_link"] = "required|url";
        }

        return $rules;
    }
}
