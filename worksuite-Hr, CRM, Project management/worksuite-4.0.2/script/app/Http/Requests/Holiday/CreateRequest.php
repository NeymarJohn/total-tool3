<?php
namespace App\Http\Requests\Holiday;

use App\Holiday;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Admin\Employee
 */
class CreateRequest extends FormRequest
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
            'date.0'  => 'required',
            'occasion.0'  => 'required',
        ];

    }

    public function messages()
    {
        return [
            'date.0.required' => 'Date is a require field.',
            'occasion.0.required' => 'Occasion is a require field.',
        ];
    }

}
