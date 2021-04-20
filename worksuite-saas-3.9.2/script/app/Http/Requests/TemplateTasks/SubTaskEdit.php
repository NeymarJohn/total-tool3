<?php
namespace App\Http\Requests\TemplateTasks;

use App\Holiday;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Admin\Employee
 */
class SubTaskEdit extends FormRequest
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
            'name'  => 'required',
        ];

    }

    public function messages()
    {
        return [
            'name.0.required' => 'Task Name is a require field.',
        ];
    }

}
