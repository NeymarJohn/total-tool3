<?php

namespace App\Observers;


use App\ProductSubCategory;

class ProductSubCategoryObserver
{

    public function saving(ProductSubCategory $category)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $category->company_id = company()->id;
        }
    }

}
