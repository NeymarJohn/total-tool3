<?php

namespace App;

use App\Observers\TicketChannelObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TicketChannel extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(TicketChannelObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
