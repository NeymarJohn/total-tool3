<?php

namespace App\Http\Requests;

use App\Helper\Reply;
use Illuminate\Foundation\Http\FormRequest;

class CoreRequest extends FormRequest
{

    protected function formatErrors(\Illuminate\Contracts\Validation\Validator  $validator)
    {
        return Reply::formErrors($validator);
    }

}