<?php

namespace App\Http\Requests;

use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class ChatStoreRequest extends CoreRequest
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
            'client_id' => 'required_if:user_type,client',
        ];
    }

    public function messages() {
        return [
            'user_id.required_if' => 'Select a user to send the message',
            'client_id.required_if' => 'Select a client to send the message',
        ];
    }

}
