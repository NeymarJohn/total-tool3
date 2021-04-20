<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateThemeSetting extends CoreRequest
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
            'theme_settings.*.header_color' => 'required',
            'theme_settings.*.sidebar_color' => 'required',
            'theme_settings.*.sidebar_text_color' => 'required',
            'theme_settings.*.link_color' => 'required'
        ];
    }
}
