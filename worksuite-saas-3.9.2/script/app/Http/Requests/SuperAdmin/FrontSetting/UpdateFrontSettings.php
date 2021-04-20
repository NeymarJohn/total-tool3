<?php

namespace App\Http\Requests\SuperAdmin\FrontSetting;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class UpdateFrontSettings extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'social_links.*' => 'nullable|url'
        ];
    }

    public function messages()
    {
        return [
            'social_links.*.url' => 'Please enter proper url format'
        ];
    }
}
