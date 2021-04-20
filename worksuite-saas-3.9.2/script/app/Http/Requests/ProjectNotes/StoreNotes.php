<?php

namespace App\Http\Requests\ProjectNotes;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotes extends FormRequest
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
            'notes_title' => 'required',
            'note_details' => 'required',
        ];

        if ($this->notes_type == 1 && is_null($this->user_id) && is_null($this->is_client_show)) {
            $rules['user_id'] = 'required';
        }
        return $rules;
    }
}
