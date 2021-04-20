<?php

namespace App\Http\Requests\Leaves;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeave extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user_id != '' && $this->user_id != null) {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'leave_type_id' => 'required',
            'duration' => 'required',
            'multi_date' => 'required_if:duration,multiple',
            'reason' => 'required',
            'status' => 'required'
        ];
    }
}
