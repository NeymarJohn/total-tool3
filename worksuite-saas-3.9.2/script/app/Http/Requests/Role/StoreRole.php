<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRole extends CoreRequest
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
        $company = company();
        return [
            'name' => [
                'required',
                Rule::unique('roles', 'name')->where(function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                })
            ],
        ];
    }
}
