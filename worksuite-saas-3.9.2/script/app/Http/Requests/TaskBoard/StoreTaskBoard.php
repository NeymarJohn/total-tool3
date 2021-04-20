<?php

namespace App\Http\Requests\TaskBoard;

use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskBoard extends CoreRequest
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
//            'column_name' => 'required|unique:taskboard_columns,column_name',
            'column_name' => [
                'required',
                Rule::unique('taskboard_columns')->where(function($query) {
                    $query->where(['column_name' => $this->request->get('column_name'), 'company_id' => company()->id]);
                })
            ],
            'label_color' => 'required'
        ];
    }
}
