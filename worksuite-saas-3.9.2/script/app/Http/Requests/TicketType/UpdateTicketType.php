<?php

namespace App\Http\Requests\TicketType;

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
        $detailID = TicketType::where('id', $this->route('ticketType'))->first();
        return [
            // 'type' => 'required|unique:ticket_types,type,'.$this->route('ticketType'),
            "type" => [
                'required',
                Rule::unique('ticket_types')->where(function($query) use($detailID) {
                    $query->where('company_id', company()->id);
                    $query->where('id', '<>' ,$detailID->id);
                })
            ],
        ];
    }
}
