<?php

namespace App\Http\Requests\SuperAdmin\Packages;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;
use App\Package;

class DeleteRequest extends SuperAdminBaseRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        $package = Package::findOrFail($this->route('package'));

        if($package->default == 'no'){
            return true;
        }

        return false;

    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            //
        ];
    }

}
