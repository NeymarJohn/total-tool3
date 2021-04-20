<?php

namespace App;

use App\Observers\ProjectRatingObserver;
use App\Scopes\CompanyScope;

class ProjectRating extends BaseModel
{

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectRatingObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
