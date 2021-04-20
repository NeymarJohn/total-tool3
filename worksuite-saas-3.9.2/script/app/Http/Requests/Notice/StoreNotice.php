<?php

namespace App\Http\Requests\Notice;

use App\Http\Requests\CoreRequest;

class StoreNotice extends CoreRequest
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
            'heading' => 'required',
        ];

        if($this->file){
            $rules['file'] = 'mimes:pdf,doc,docx,jpg,jpeg,png,webp,xls,xlsx,zip';
        }
        return $rules;

    }
}
