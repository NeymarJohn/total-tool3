<?php

namespace App;

use App\Observers\ProjectRatingObserver;

class ProjectRating extends BaseModel
{

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectRatingObserver::class);

    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
