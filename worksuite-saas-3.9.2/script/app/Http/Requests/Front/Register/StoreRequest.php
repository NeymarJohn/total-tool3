<?php

namespace App\Http\Requests\Front\Register;

use App\GlobalSetting;
use App\Http\Requests\CoreRequest;
use App\User;
use Illuminate\Validation\Rule;

class StoreRequest extends CoreRequest
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
        $global = GlobalSetting::first();

        $rules = [
            'company_name' => 'required',
            'email' => 'required',
            'sub_domain' => module_enabled('Subdomain') ? 'required|min:4|unique:companies,sub_domain|max:50|sub_domain' : '',
            'password' => request()->has('password_confirmation') ? 'required||confirmed' : 'required|string',
            'password_confirmation' => request()->has('password_confirmation') ? 'required' : ''
        ];

        if ($global->google_recaptcha_status) {
            $rules['g-recaptcha-response'] = 'required';
        }

        $user = User::where('users.email', request()->email)->first();
        if ($user) {
            $user->hasRole('employee') ?  $rules['email'] = 'required|email|unique:users' : '';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Please select google recaptcha'
        ];
    }

    public function prepareForValidation()
    {
        if (empty($this->sub_domain)) {
            return;
        }

        // Add servername domain suffix at the end
        $subdomain = trim($this->sub_domain, '.') . '.' . get_domain();
        $this->merge(['sub_domain' => $subdomain]);
        request()->merge(['sub_domain' => $subdomain]);
    }
}
