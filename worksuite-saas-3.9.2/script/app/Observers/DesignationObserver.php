<?php

namespace App\Observers;

use App\Designation;

class DesignationObserver
{

    public function saving(Designation $designation)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $designation->company_id = company()->id;
        }
    }

}
