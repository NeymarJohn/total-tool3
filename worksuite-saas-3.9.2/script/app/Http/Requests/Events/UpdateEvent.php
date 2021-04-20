<?php

namespace App\Http\Requests\Events;

use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEvent extends CoreRequest
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
            'event_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'all_employees' => 'sometimes',
            'where' => 'required',
            'description' => 'required',
        ];
    }
}
