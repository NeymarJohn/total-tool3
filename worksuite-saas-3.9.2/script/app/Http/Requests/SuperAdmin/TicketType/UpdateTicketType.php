<?php

namespace App\Http\Requests\SuperAdmin\TicketType;

use App\TicketType;
use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Validation\Rule;

class UpdateTicketType extends CoreRequest
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
             'type' => 'required|unique:support_ticket_types,type,'.$this->route('support-ticketType'),
        ];
    }
}
