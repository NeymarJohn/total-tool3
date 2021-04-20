<?php

namespace App\Http\Requests\SuperAdmin\FrontFaqSetting;

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
        $data = [
            'question' => 'required',
            'answer' => 'required',
        ];

        return $data;
    }
}
