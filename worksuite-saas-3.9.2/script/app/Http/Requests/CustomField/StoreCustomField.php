<?php

namespace App\Http\Requests\CustomField;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class StoreCustomField extends CoreRequest
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
        Validator::extend('not_custom_fields', function($attribute, $value, $parameters, $validator) {
            $userColumns = Schema::getColumnListing('users');

            if(!in_array($this->get('label'), $userColumns)){
                return true;
            }

            return false;
        });

        return [
            'label'     => 'required',
            'name'      => 'required|alpha_dash',
            'required'  => 'required',
            'type'      => 'required'
        ];
    }
}
