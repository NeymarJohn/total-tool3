<?php

namespace App\Http\Requests\Tickets;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicket extends CoreRequest
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
            'subject' => 'required',
            'description' => 'required',
            'priority' => 'required',
            'user_id' => 'required'
        ];
    }

    public function messages() {
        return [
            'user_id.required' => __('modules.tickets.requesterName').' '.__('app.required')
        ];
    }
}
