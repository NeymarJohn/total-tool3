<?php

namespace App;

class TaskTag extends BaseModel
{
    protected $guarded = ['id'];

    public function tag(){
        return $this->belongsTo(TaskTagList::class, 'tag_id');
    }
}
