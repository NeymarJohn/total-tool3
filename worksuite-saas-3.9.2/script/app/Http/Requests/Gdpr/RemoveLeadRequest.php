<?php
namespace App\Http\Requests\Gdpr;

use App\Holiday;
use App\Http\Requests\CoreRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Admin\Employee
 */
class RemoveLeadRequest extends CoreRequest
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
        return [
            'description'  => 'required',
        ];

    }

}
