<?php

namespace App\Http\Requests\Message;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class ClientChatStore extends CoreRequest
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
            'message' => 'required',
            'user_id' => 'required_if:user_type,employee',
            'admin_id' => 'required_if:user_type,admin',
        ];
    }

    public function messages() {
        return [
            'user_id.required_if' => 'Select a user to send the message',
            'admin_id.required_if' => 'Select an admin to send the message',
        ];
    }
}
