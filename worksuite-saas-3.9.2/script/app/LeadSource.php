<?php

namespace App;

use App\Observers\LeadSourceObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LeadSource extends BaseModel
{
    protected $table = 'lead_sources';

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::observe(LeadSourceObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
