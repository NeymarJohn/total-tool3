<?php

namespace App\Http\Requests\Admin\Client;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientSubcategory extends CoreRequest
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
            'category_name' => 'required',
            'category_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'category_name.required' => 'The subcategory name field is required.',
            'category_id.required' => 'The category name field is required.',
            
        ];
    }
}
