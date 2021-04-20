<?php

namespace App;

use App\Observers\TicketReplyTemplateObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TicketReplyTemplate extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(TicketReplyTemplateObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
