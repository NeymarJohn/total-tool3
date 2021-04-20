<?php

namespace App\Http\Requests\Expenses;

use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreRecurringExpense extends CoreRequest
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
        $rules = [
            'item_name' => 'required',
            'price' => 'required|numeric',
            'billing_cycle' => 'required',
        ];

        if($this->get('rotation') == 'weekly' || $this->get('rotation') == 'bi-weekly'){
            $rules['day_of_week'] = 'required';
        }
        elseif ($this->get('rotation') == 'monthly' || $this->get('rotation') == 'quarterly' || $this->get('rotation') == 'half-yearly' || $this->get('rotation') == 'annually'){
            $rules['day_of_month'] = 'required';
        }


        return $rules;
    }

}
