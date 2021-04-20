<?php

namespace App;


class ProductSubCategory extends BaseModel
{
    protected $table = 'product_sub_category';

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
