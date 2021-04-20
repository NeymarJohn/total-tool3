<?php

namespace App;

use App\Observers\CurrencyObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;

class Currency extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(CurrencyObserver::class);
        static::addGlobalScope('enable', function (Builder $builder) {
            $builder->where('currencies.status', '=', 'enable');
        });
        static::addGlobalScope(new CompanyScope);
    }
}
