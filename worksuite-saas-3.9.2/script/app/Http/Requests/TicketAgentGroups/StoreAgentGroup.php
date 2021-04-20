<?php

namespace App\Http\Requests\TicketAgentGroups;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreAgentGroup extends CoreRequest
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
            'user_id.0' => 'required',
            'group_id' => 'required'
        ];
    }

    public function messages() {
        return [
            'user_id.0.required' => __('messages.atleastOneValidation').' '.__('modules.tickets.agent'),
            'group_id.required' => __('modules.tickets.groupName').' '.__('app.required')
        ];
    }
}
