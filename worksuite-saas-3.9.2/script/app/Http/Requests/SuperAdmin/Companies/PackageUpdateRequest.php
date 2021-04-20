<?php

namespace App\Http\Requests\SuperAdmin\Companies;


use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class PackageUpdateRequest extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pay_date' => 'required',
            'package' => 'required|exists:packages,id',
            'packageType' => 'required|in:monthly,annual',
            'payment_method' => 'required|exists:offline_payment_methods,id',
        ];
    }
}
