<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRole extends FormRequest
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
            'value' => [
                'required',
                Rule::unique('roles', 'name')->where(function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                })->ignore($this->route('role_permission'))
            ],
        ];
    }
}
