<?php

namespace App\Http\Requests\SuperAdmin\FooterSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;
use App\Package;

class FooterTextRequest extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [
            'footer_copyright_text' => 'required',
        ];

        return $data;
    }
}
