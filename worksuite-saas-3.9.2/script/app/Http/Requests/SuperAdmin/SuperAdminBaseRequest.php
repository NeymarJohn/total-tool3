<?php

namespace App\Http\Requests\SuperAdmin;

use App\Http\Requests\CoreRequest;

class SuperAdminBaseRequest extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !empty(superAdmin());
    }
}
