<?php

namespace App\Http\Requests\DiscussionCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|unique:discussion_categories,name,null,null,company_id,' . company_setting()->id,
            'color' => 'required'
        ];
    }
}
