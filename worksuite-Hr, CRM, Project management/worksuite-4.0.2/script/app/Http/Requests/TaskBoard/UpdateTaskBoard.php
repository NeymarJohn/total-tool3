<?php

namespace App\Http\Requests\TaskBoard;

use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskBoard extends CoreRequest
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
            'column_name' => 'required|unique:taskboard_columns,column_name,'.$this->route('taskboard'),
            'label_color' => 'required'
        ];
    }
}
