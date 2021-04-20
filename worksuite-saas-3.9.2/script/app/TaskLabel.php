<?php

namespace App;

class TaskLabel extends BaseModel
{
    protected $guarded = ['id'];

    public function label(){
        return $this->belongsTo(TaskLabelList::class, 'label_id');
    }
}
