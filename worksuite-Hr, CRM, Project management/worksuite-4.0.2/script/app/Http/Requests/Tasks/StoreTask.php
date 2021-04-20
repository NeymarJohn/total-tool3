<?php

namespace App\Http\Requests\Tasks;

use App\CustomField;
use App\Http\Requests\CoreRequest;
use App\Project;
use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
use App\Task;

class StoreTask extends CoreRequest
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
        $setting = global_setting();
        $user = auth()->user();
        $rules = [
            'heading' => 'required',
            'due_date' => ['required' , new CheckDateFormat(null,$setting->date_format) , new CheckEqualAfterDate('start_date',$setting->date_format)],
            'priority' => 'required'
        ];

        if (request()->has('project_id') && request()->project_id != "all" && request()->project_id != "") {
            $project = Project::find(request()->project_id);

            $startDate = $project->start_date->format($setting->date_format);
            $rules['start_date'] = ['required', new CheckDateFormat(null,$setting->date_format), new CheckEqualAfterDate('start_date',$setting->date_format, $startDate, __('messages.projectDateValidation', ['date' => $startDate]))];
        } else {
            $rules['start_date'] = ['required', new CheckDateFormat(null,$setting->date_format)];
        }

        if ($this->has('dependent') && $this->dependent == 'yes' && $this->dependent_task_id != '') {
            $dependentTask = Task::find($this->dependent_task_id);
            $endDate = $dependentTask->due_date->format($setting->date_format);
            $rules['start_date'] = ['required', new CheckDateFormat(null,$setting->date_format), new CheckEqualAfterDate('',$setting->date_format, $endDate, __('messages.taskDateValidation', ['date' => $endDate]) )];
        }

        if ($user->can('add_tasks') || $user->hasRole('admin') || $user->hasRole('client')) {
            $rules['user_id'] = 'required';
        }

        if ($this->has('repeat') && $this->repeat == 'yes') {
            $rules['repeat_cycles'] = 'required|numeric';
        }

        if ($this->has('set_time_estimate')) {
            $rules['estimate_hours'] = 'required|integer|min:0';
            $rules['estimate_minutes'] = 'required|integer|min:0';
        }

        if (request()->get('custom_fields_data')) {
            $fields = request()->get('custom_fields_data');
            foreach ($fields as $key => $value) {
                $idarray = explode('_', $key);
                $id = end($idarray);
                $customField = CustomField::findOrFail($id);
                if ($customField->required == "yes" && (is_null($value) || $value == "")) {
                    $rules["custom_fields_data[$key]"] = 'required';
                }
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'project_id.required' => __('messages.chooseProject'),
            'user_id.required' => 'Choose an assignee',
        ];
    }

    public function attributes()
    {
        $attributes = [];
        if (request()->get('custom_fields_data')) {
            $fields = request()->get('custom_fields_data');
            foreach ($fields as $key => $value) {
                $idarray = explode('_', $key);
                $id = end($idarray);
                $customField = CustomField::findOrFail($id);
                if ($customField->required == "yes") {
                    $attributes["custom_fields_data[$key]"] = $customField->label;
                }
            }
        }
        return $attributes;
    }
}
