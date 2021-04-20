<?php

namespace App\Observers;

use App\Tax;

class TaxObserver
{

    public function saving(Tax $tax)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $tax->company_id = company()->id;
        }
    }

}
