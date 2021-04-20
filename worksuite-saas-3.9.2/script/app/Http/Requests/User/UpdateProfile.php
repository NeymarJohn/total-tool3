<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends CoreRequest
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
            'email' => 'required|unique:users,email,'.$this->route('profile'),
            'name'  => 'required',
            'password'  => 'nullable|min:6',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ];
    }

    public function messages() {
        return [
          'image.image' => 'Profile picture should be an image'
        ];
    }
}
