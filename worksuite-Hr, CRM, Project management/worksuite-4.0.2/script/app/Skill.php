<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends BaseModel
{
    protected $table = 'skills';
    protected $fillable = ['name'];
}
