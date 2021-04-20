<?php

namespace App\Http\Requests\Invoices;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreRecurringInvoice extends CoreRequest
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
        $this->has('show_shipping_address') ? $this->request->add(['show_shipping_address' => 'yes']) : $this->request->add(['show_shipping_address' => 'no']);

        $rules = [
            'issue_date' => 'required',
            'due_date' => 'required',
            'sub_total' => 'required',
            'total' => 'required',
            'currency_id' => 'required',
            'shipping_address' => 'sometimes|required'
        ];

        if ($this->project_id == '') {
            $rules['client_id'] = 'required';
        }

        if($this->get('rotation') == 'weekly' || $this->get('rotation') == 'bi-weekly'){
            $rules['day_of_week'] = 'required';
        }
        elseif ($this->get('rotation') == 'monthly' || $this->get('rotation') == 'quarterly' || $this->get('rotation') == 'half-yearly' || $this->get('rotation') == 'annually'){
            $rules['day_of_month'] = 'required';
        }


        return $rules;
    }
}
