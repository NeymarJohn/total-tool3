<?php

namespace App\Http\Requests\SuperAdmin\FrontMenuSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;
use App\Package;

class UpdateRequest extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [
            'home'           => 'required',
            'feature'        => 'required',
            'contact'        => 'required',
            'price'          => 'required',
            'get_start'      => 'required',
            'login'          => 'required',
            'contact_submit' => 'required',
        ];

        return $data;
    }
}
