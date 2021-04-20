<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class InvoiceFileStore
 * @package App\Http\Requests
 */
class InvoiceFileStore extends FormRequest
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
        return [
            'invoice_id' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,jpg,jpeg,png,webp,xls,xlsx'
        ];
    }

}
