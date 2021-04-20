<?php
namespace App\Http\Requests\Gdpr;

use App\Http\Requests\CoreRequest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Admin\Employee
 */
class SaveConsentUserDataRequest extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        // If admin
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'additional_description'  => 'required',
        ];

        if($this->has('consent_description'))
        {
            $rules['consent_description'] = 'required';
        }

        return $rules;
    }

}
