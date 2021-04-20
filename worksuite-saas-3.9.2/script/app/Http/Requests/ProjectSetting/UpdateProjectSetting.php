<?php

namespace App\Http\Requests\ProjectSetting;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectSetting extends CoreRequest
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
            'send_reminder' => 'sometimes|required',
            'remind_to' => 'required_with:send_reminder|array',
            'remind_time' => 'required|integer',
            'remind_type' => ['required', Rule::in(['days'])]
        ];
    }

    public function messages()
    {
        return [
            'remind_to.required_with' => 'Select atleast one option to continue'
        ];
    }
}
