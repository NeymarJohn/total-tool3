<?php

namespace App;

use App\Observers\TicketTypeObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TicketType extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(TicketTypeObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
