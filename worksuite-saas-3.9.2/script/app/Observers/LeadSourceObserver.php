<?php

namespace App\Observers;

use App\LeadSource;

class LeadSourceObserver
{

    public function saving(LeadSource $source)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $source->company_id = company()->id;
        }
    }

}
