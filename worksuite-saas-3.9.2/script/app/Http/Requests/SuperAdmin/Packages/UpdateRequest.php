<?php

namespace App\Http\Requests\SuperAdmin\Packages;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;
use App\Package;
use App\StripeSetting;

class UpdateRequest extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [
            'name' => 'required|unique:packages,name,' . $this->route('package'),
            'description' => 'required',
//            'annual_price' => 'required',
//            'monthly_price' => 'required',
            'max_employees' => 'required|numeric',
            'max_storage_size' => 'required|gte:-1',
            'storage_unit' => 'required|in:gb,mb',
        ];

        if(!$this->has('is_free')){
            $data['annual_price'] = 'required';
            $data['monthly_price'] = 'required';
        }

        $package = Package::findOrFail($this->route('package'));

        if($package->default == 'no'){
            $data['module_in_package'] = 'required';
        }

        if($this->get('annual_price') > 0 && $this->get('monthly_price') > 0  ){
            $data['stripe_annual_plan_id'] = 'required';
            $data['stripe_monthly_plan_id'] = 'required';
        }

        $stripe = StripeSetting::first();

        if(($this->get('annual_price') > 0 && $this->get('monthly_price') > 0 ) &&  $stripe->razorpay_status == 'active'){
            $data['razorpay_annual_plan_id'] = 'required';
            $data['razorpay_monthly_plan_id'] = 'required';
        }

        return $data;
    }

    public function messages()
    {
        return [
            'module_in_package.required' => 'Select atleast one module.'
        ];
    }
}
