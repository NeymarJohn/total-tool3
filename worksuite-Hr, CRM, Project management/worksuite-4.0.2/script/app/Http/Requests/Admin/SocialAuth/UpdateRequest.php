<?php

namespace App\Http\Requests\Admin\SocialAuth;


use App\Http\Requests\CoreRequest;

class UpdateRequest extends CoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'google_client_id' => 'required_if:google_status,on',
            'google_secret_id' => 'required_if:google_status,on',
            'facebook_client_id' => 'required_if:facebook_status,on',
            'facebook_secret_id' => 'required_if:facebook_status,on',
            'twitter_client_id' => 'required_if:twitter_status,on',
            'twitter_secret_id' => 'required_if:twitter_status,on',
            'linkedin_client_id' => 'required_if:linkedin_status,on',
            'linkedin_secret_id' => 'required_if:linkedin_status,on',

        ];
    }
}
