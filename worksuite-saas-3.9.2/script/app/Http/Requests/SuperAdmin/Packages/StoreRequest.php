<?php

namespace App\Http\Requests\SuperAdmin\Packages;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;
use App\Package;
use App\StripeSetting;

class StoreRequest extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [
            'name' => 'required|unique:packages',
            'description' => 'required',
//            'annual_price' => 'required',
//            'monthly_price' => 'required',
            'max_employees' => 'required|numeric',
            'module_in_package' => 'required',
            'max_storage_size' => 'required|gte:-1',
            'storage_unit' => 'required|in:gb,mb',
        ];


        if(!$this->has('is_free')){
            $data['annual_price'] = 'required';
            $data['monthly_price'] = 'required';
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



        if($this->get('annual_price') > 0 && $this->get('monthly_price') > 0  ){
            $data['stripe_annual_plan_id'] = 'required';
            $data['stripe_monthly_plan_id'] = 'required';
        }

        return $data;
    }

    public function messages()
    {
        return [
            'module_in_package.required' => 'Select at-least one module.',
            'max_storage_size.gte' => 'size should be -1 or greater then -1.'
        ];

    }
}
