<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends BaseModel
{
   protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {
        return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('task-files/'.$this->task_id.'/'.$this->hashname);
    }

}
