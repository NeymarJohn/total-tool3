<?php

namespace App;

use App\Observers\UniversalSearchObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UniversalSearch extends BaseModel
{
    protected $table = 'universal_search';

    protected static function boot()
    {
        parent::boot();

        static::observe(UniversalSearchObserver::class);

        static::addGlobalScope(new CompanyScope);

    }
}
