<?php

namespace App;

use App\Observers\ClientCategoryObserver;
use App\Scopes\CompanyScope;

class ClientCategory extends BaseModel
{
    protected $table = 'client_categories';

    protected static function boot()
    {
        parent::boot();
        static::observe(ClientCategoryObserver::class);
        static::addGlobalScope(new CompanyScope());
    }
}
