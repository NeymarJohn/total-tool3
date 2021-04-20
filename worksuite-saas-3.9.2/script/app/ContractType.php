<?php

namespace App;

use App\Observers\ContractObserver;
use App\Observers\ContractTypeObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContractType extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(ContractTypeObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
