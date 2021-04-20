<?php

namespace App;

use App\Observers\FileUploadObserver;

class ProjectFile extends BaseModel
{
    protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {

        return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('project-files/'.$this->project_id.'/'.$this->hashname);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }


    protected static function boot()
    {
        parent::boot();
        static::observe(FileUploadObserver::class);
    }
}
