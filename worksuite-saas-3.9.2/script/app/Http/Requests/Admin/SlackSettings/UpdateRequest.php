<?php

namespace App\Http\Requests\Admin\SlackSettings;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends CoreRequest
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
        return [
            "slack_logo" => "image|mimes:jpg,png,jpeg,gif,svg|max:4096",
        ];
    }

    public function messages()
    {
        return [
            'slack_logo.uploaded' => trans('messages.fileSize'),
        ];
    }
}
