<?php

namespace App;

use App\Observers\TeamObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Team extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(TeamObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function members()
    {
        return $this->hasMany(EmployeeTeam::class, 'team_id');
    }

    public function member()
    {
        return $this->hasMany(EmployeeDetails::class, 'department_id');
    }
}
