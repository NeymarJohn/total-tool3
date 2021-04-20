<?php

namespace App;

use App\Observers\ProjectMilsetoneObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProjectMilestone extends BaseModel
{

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectMilsetoneObserver::class);

        static::addGlobalScope(new CompanyScope);
    }


    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withoutGlobalScopes(['enable']);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }
}
