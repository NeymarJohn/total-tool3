<?php

namespace App\Http\Requests\SuperAdmin\Language;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends CoreRequest
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
            "language_name" => "required|unique:language_settings,language_name",
            "language_code" => "required|unique:language_settings,language_code",
            "status" => "required",
        ];
    }
}
