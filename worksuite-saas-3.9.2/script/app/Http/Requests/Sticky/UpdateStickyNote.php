<?php

namespace App\Http\Requests\Sticky;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateStickyNote
 * @package App\Http\Requests\Sticky
 */
class UpdateStickyNote extends CoreRequest
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
            'notetext' => 'required'
        ];
    }

}
