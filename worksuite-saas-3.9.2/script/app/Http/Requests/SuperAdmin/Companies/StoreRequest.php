<?php

namespace App\Http\Requests\SuperAdmin\Companies;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;
use App\Scopes\CompanyScope;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreRequest extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        \Illuminate\Support\Facades\Validator::extend('check_client', function($attribute, $value, $parameters, $validator)  {
                $user = User::withoutGlobalScopes(['active', CompanyScope::class])
                    ->join('client_details', 'client_details.user_id', 'users.id')
                    ->where('users.email', $value)
                    ->first();

                $userTable = User::withoutGlobalScopes(['active', CompanyScope::class])
                                ->where('users.email', $value)->first();

                if(!is_null($user) && (!is_null($userTable) && !$userTable->hasRole('admin'))){
                    return true;
                }

                elseif((!is_null($userTable) && is_null($user) && $userTable->hasRole('admin')) ){
                    return false;
                }
                elseif(is_null($userTable) && is_null($user)){
                    return true;
                }
                return false;

        });

        return [
            "company_name" => "required",
            "company_email" => "required|email|unique:companies",
            'sub_domain' => module_enabled('Subdomain') ?'required|min:4|unique:companies,sub_domain|max:50|sub_domain':'',
            "company_phone" => "required",
            "address" => "required",
            "status" => "required",
            'email' => 'required|check_client',
            'password' => 'required|min:6'

        ];

    }

    public function prepareForValidation()
    {
        if (empty($this->sub_domain)) {
            return;
        }

        // Add servername domain suffix at the end
        $subdomain = trim($this->sub_domain, '.') . '.' . get_domain();
        $this->merge(['sub_domain' => $subdomain]);
        request()->merge(['sub_domain' => $subdomain]);
    }

    public function messages()
    {
       return [
           'email.check_client' => 'The email has already been taken.'
       ];
    }
}
