<?php

namespace App\Http\Requests\TimeLogs;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeLog extends CoreRequest
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
        $rules =  [
            'start_time' => 'required',
            'end_time' => 'required',
            'memo' => 'required',
        ];

        if ($this->has('user_id')) {
            $rules['user_id'] = 'required';
        }

        if ($this->has('task_id')) {
            $rules['task_id'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'project_id.required' => __('messages.chooseProject')
        ];
    }
}
