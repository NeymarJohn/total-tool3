<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait CustomFieldsTrait
{
    public $model;
    private $extraData;

    /** Get company ID for current object
     * @return int Returns current object's company id
     */

    private function getModelName()
    {
        $model       = new \ReflectionClass($this);
        $this->model = $model;

        return $this->model->getName();
    }

    public function addCustomFieldGroup($group)
    {

        $modelName = $this->getModelName();

        $insert = [
            'name'  => $group['name'],
            'model' => $modelName
        ];

        \DB::table('custom_field_groups')->insert($insert);

        // Add Custom Fields for this group
        foreach ($group['fields'] as $field) {
            $insertData = [
                'custom_field_group_id' => $field['groupID'],
                'label'                 => $field['label'],
                'name'                  => $field['name'],
                'type'                  => $field['type']
            ];

            if (isset($field['required']) && ((strtolower($field['required']) == 'yes' || strtolower($field['required']) == 'on' || $field['required'] == 1))) {
                $insertData['required'] = 'yes';
                
            } else {
                $insertData['required'] = 'no';
            }

            // Single value should be stored as text (multi value JSON encoded)
            if (isset($field['value'])) {
                if (is_array($field['value'])) {
                    $insertData['value'] = \GuzzleHttp\json_encode($field['value']);
                    
                } else {
                    $insertData['value'] = $field['value'];
                }
            }

            \DB::table('custom_fields')->insert($insertData);
        }
    }

    public function addCustomField($group)
    {

        $modelName = $this->getModelName();


        // Add Custom Fields for this group
        foreach ($group['fields'] as $field) {
            $insertData = [
                'custom_field_group_id' => $field['groupID'],
                'label'                 => $field['label'],
                'name'                  => $field['name'],
                'type'                  => $field['type']
            ];

            if (isset($field['required']) && ((strtolower($field['required']) == 'yes' || strtolower($field['required']) == 'on' || $field['required'] == 1))) {
                $insertData['required'] = 'yes';

            } else {
                $insertData['required'] = 'no';
            }

            // Single value should be stored as text (multi value JSON encoded)
            if (isset($field['values'])) {
                if (is_array($field['values'])) {
                    $insertData['values'] = \GuzzleHttp\json_encode($field['values']);

                } else {
                    $insertData['values'] = $field['values'];
                }
            }

            \DB::table('custom_fields')->insert($insertData);
        }
    }
    
    public function updateCustomField($group)
    {

        $modelName = $this->getModelName();


        // Add Custom Fields for this group
        foreach ($group['fields'] as $field) {
            $insertData = [
                'custom_field_group_id' => 1,
                'label'                 => $field['label'],
                'name'                  => $field['name'],
                'type'                  => $field['type']
            ];

            if (isset($field['required']) && ((strtolower($field['required']) == 'yes' || strtolower($field['required']) == 'on' || $field['required'] == 1))) {
                $insertData['required'] = 'yes';

            } else {
                $insertData['required'] = 'no';
            }

            // Single value should be stored as text (multi value JSON encoded)
            if (isset($field['value'])) {
                if (is_array($field['value'])) {
                    $insertData['values'] = \GuzzleHttp\json_encode($field['value']);

                } else {
                    $insertData['values'] = $field['value'];
                }
            }

            \DB::table('custom_fields')->insert($insertData);
        }
    }

    public function getCustomFieldGroups()
    {
        return \DB::table('custom_field_groups')
            ->select('id', 'name')
            ->where('model', $this->getModelName())
            ->get();
    }

    public function updateCustomFieldGroup($group)
    {

        // Update group
        \DB::table('custom_field_groups')
            ->where('id', $group['id'])
            ->update(['name' => $group['name']]);

        // Update custom fields

        $foundCustomFields = [];

        // Add Custom Fields for this group
        foreach ($group['fields'] as $field) {

            $insertData = ['custom_field_group_id' => $field['groupID'],
                           'label'                 => $field['label'],
                           'name'                  => $field['name'],
                           'type'                  => $field['type']];


            if (isset($field['required']) && ((strtolower($field['required']) == 'yes' || strtolower($field['required']) == 'on' || $field['required'] == 1))) {
                $insertData['required'] = 'yes';
                
            } else {
                $insertData['required'] = 'no';
            }

            // Single value should be stored as text (multi value JSON encoded)
            if (isset($field['value'])) {
                if (is_array($field['value'])) {
                    $insertData['values'] = \GuzzleHttp\json_encode($field['value']);

                } else {
                    $insertData['values'] = $field['value'];
                }
            }

            if (isset($field['id']) && $field['id'] != '' && $field['id'] != null && $field['id'] != 0) {
                // Custom field exists, update
                \DB::table('custom_fields')->update($insertData);
                $foundCustomFields[] = $field['id'];
                
            } else {
                $foundCustomFields[] = \DB::table('custom_fields')->insertGetId($insertData);
            }
        }

        // Delete the custom fields not found
        \DB::table('custom_fields')->whereIn('id', $foundCustomFields)->delete();
    }

    public function getCustomFieldGroupsWithFields()
    {
        $fields = [];

        $groups = $this->getCustomFieldGroups();

        foreach ($groups as $group) {
            $customFields = \DB::table('custom_fields')
                ->select('id', 'label', 'name', 'type', 'required', 'values')
                ->where('custom_field_group_id', $group->id)->get();

            $customFields = collect($customFields);

            // convert values to json array if type is select
            $customFields = $customFields->map(function ($item) {
                if ($item->type == 'select' || $item->type == 'radio' || $item->type == 'checkbox') {
                    $item->values = json_decode($item->values);

                    return $item;
                }

                return $item;
            });

            $group->fields = $customFields;
            $fields[]      = $group;

        }

        if (!empty($fields)) {
            return $fields[0];
        } else {
            return $fields;
        }
    }

    public function getCustomFieldsData()
    {

        $modelId = $this->id;

        // Get custom fields for this modal
        /** @var Collection $data */
        $data = \DB::table('custom_fields_data')
            ->rightJoin('custom_fields', function ($query) use ($modelId) {
                $query->on('custom_fields_data.custom_field_id', '=', 'custom_fields.id');
                $query->on('model_id', '=', \DB::raw($modelId));
            })
            ->rightJoin('custom_field_groups', 'custom_fields.custom_field_group_id', '=', 'custom_field_groups.id')
            ->select('custom_fields.id', \DB::raw('CONCAT("field_", custom_fields.id) as field_id'), 'custom_fields.type', 'custom_fields_data.value')
            ->where('custom_field_groups.model', $this->getModelName())
            ->get();

        $data = collect($data);

        $data = $data->map(function ($item) {
            if ($item->type == 'checkbox') {
                $item->value = ($item->value == '1') ? true : false;

                return $item;
                
            } else if ($item->type == 'number') {
//                $item->value = ($item->value * 1);

                return $item;
                
            } else {
                return $item;
            }
        });

        // Convert collection to an associative array
        // of format ['field_{id}' => $value]
        $result = $data->pluck('value', 'field_id');

        return $result;
    }

    public function updateCustomFieldData($fields)
    {
        foreach ($fields as $key => $value) {
            $idarray = explode('_', $key);
            $id = end($idarray);
            // Find is entry exists
            $entry = \DB::table('custom_fields_data')
                ->where('model', $this->getModelName())
                ->where('model_id', $this->id)
                ->where('custom_field_id', $id)
                ->first();

            if ($entry) {
                \DB::table('custom_fields_data')
                    ->where('model', $this->getModelName())
                    ->where('model_id', $this->id)
                    ->where('custom_field_id', $id)
                    ->update(['value' => $value]);
                
            } else {
                \DB::table('custom_fields_data')
                    ->insert(['model'           => $this->getModelName(),
                              'model_id'        => $this->id,
                              'custom_field_id' => $id,
                              'value'           => ($value) ? $value : '']);
            }
        }
    }

    public function getExtrasAttribute()
    {
        if ($this->extraData == null) {
            $this->extraData = $this->getCustomFieldGroupsWithFields();
        }

        return $this->extraData;
    }

    public function withCustomFields()
    {
        $this->custom_fields      = $this->getCustomFieldGroupsWithFields();
        $this->custom_fields_data = $this->getCustomFieldsData();

        return $this;
    }
}