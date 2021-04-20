<?php

namespace App\Http\Requests\CreditNotes;

use App\Http\Requests\CoreRequest;
use Illuminate\Validation\Rule;

class StoreCreditNotes extends CoreRequest
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
            // 'project_id' => 'required',
            'cn_number' => Rule::unique('credit_notes'),
            'issue_date' => 'required',
            'due_date' => 'required',
            'sub_total' => 'required',
            'total' => 'required',
            'currency_id' => 'required',
            'invoice_id' => Rule::unique('credit_notes'),
        ];
        return $rules;
    }
}
