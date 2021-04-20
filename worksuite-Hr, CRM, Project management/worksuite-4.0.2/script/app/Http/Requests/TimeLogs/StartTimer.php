<?php

namespace App\Http\Requests\TimeLogs;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StartTimer extends CoreRequest
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
            'task_id' => 'required_without:create_task',
            'memo' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'project_id.required' => __('messages.chooseProject')
        ];
    }
}
