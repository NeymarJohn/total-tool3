<?php

namespace App;

class PackageSetting extends BaseModel
{
    protected $table = 'package_settings';

    protected $appends = ['all_packages'];

    public function getAllPackagesAttribute()
    {
        return count(json_decode($this->modules, true)) >= 20 ? true : false;
    }
}
