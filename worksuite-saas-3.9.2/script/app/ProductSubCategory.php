<?php

namespace App;

use App\Observers\ProductSubCategoryObserver;
use App\Scopes\CompanyScope;

class ProductSubCategory extends BaseModel
{
    protected $table = 'product_sub_category';

    protected static function boot()
    {
        parent::boot();

        static::observe(ProductSubCategoryObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
