<?php

namespace App;

use Froiden\RestAPI\ApiModel;

class ProjectCategory extends ApiModel
{
    protected $table = 'project_category';
    protected $default = ['id','category_name'];

    public function project()
    {
        return $this->hasMany(Project::class);
    }
}
