<?php

namespace App;

use App\Observers\ProductCategoryObserver;
use App\Scopes\CompanyScope;

class ProductCategory extends BaseModel
{
    protected $table = 'product_category';

    protected static function boot()
    {
        parent::boot();

        static::observe(ProductCategoryObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
