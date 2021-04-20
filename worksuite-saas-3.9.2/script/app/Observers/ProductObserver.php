<?php

namespace App\Observers;

use App\Product;

class ProductObserver
{

    public function saving(Product $product)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $product->company_id = company()->id;
        }
    }

}
