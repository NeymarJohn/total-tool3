<?php

namespace App\Http\Requests\Milestone;

use Illuminate\Foundation\Http\FormRequest;

class StoreMilestone extends FormRequest
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
        $rules = [
            'project_id' => 'required',
            'milestone_title' => 'required',
            'summary' => 'required'
        ];

        if ($this->cost != '' && $this->cost > 0) {
            $rules['currency_id'] = 'required';
        }

        return $rules;
    }
}
