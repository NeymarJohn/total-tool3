<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectSetting extends BaseModel
{
    public function getRemindToAttribute($value)
    {
        return json_decode($value);
    }

    public function setRemindToAttribute($value)
    {
        $this->attributes['remind_to'] = json_encode($value);
    }
}
