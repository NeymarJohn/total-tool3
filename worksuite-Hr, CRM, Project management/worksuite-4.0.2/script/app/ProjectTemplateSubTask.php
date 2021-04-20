<?php

namespace App;


class ProjectTemplateSubTask extends BaseModel
{
    protected $dates = ['start_date','due_date'];

    protected static function boot()
    {
        parent::boot();

    }

    public function task()
    {
        return $this->belongsTo(ProjectTemplateTask::class);
    }
}
