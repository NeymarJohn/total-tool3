<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends BaseModel
{
    protected $fillable = ['team_name'];

    public function members()
    {
        return $this->hasMany(EmployeeTeam::class, 'team_id');
    }

    public function team_members()
    {
        return $this->hasMany(EmployeeDetails::class, 'department_id');
    }

}
