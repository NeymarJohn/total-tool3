<?php

namespace App\Http\Requests\ProjectNotes;

use App\Http\Requests\CoreRequest;

class UpdateNotes extends CoreRequest
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

        if (is_null($this->notes_type) && !$this->has('$this->notes_type')) {
            $rules['user_id'] = 'required';
            //$rules['client_id'] = 'required';
        }
        if ($this->notes_type == 1 && is_null($this->user_id) && is_null($this->is_client_show)) {
            $rules['user_id'] = 'required';
            $rules['is_client_show'] = 'required';
        }
        return $rules;
    }
}
