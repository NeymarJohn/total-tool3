<?php

namespace App\Http\Requests\Settings;

use App\Http\Requests\CoreRequest;

class StorageSettingUpdate extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->storage !== 'aws') {
            return [];
        }
        
        return [
            "aws_key" => "required",
            "aws_secret" => "required",
            "aws_region" => "required",
            "aws_bucket" => "required",
        ];
    }
}
