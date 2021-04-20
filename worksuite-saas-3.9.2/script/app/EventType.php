<?php

namespace App;

use App\Observers\EventTypeObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EventType extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(EventTypeObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
