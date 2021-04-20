<?php

namespace App\Http\Requests\SubTask;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubTask extends FormRequest
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
            'name' => 'required',
            'due_date' => 'required'
        ];
    }
}
