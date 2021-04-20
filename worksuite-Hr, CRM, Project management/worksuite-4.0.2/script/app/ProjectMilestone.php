<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectMilestone extends BaseModel
{

    public function currency() {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }

}
