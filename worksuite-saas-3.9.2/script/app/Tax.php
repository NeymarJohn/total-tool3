<?php

namespace App;

use App\Observers\TaxObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tax extends BaseModel
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::observe(TaxObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
