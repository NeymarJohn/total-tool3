<?php

namespace App\Http\Requests\SuperAdmin\Profile;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class UpdateSuperAdmin extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users,email,'.$this->route('profile'),
            'name'  => 'required',
        ];
    }
}
