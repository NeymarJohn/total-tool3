<?php

namespace App\Observers;

use App\AcceptEstimate;

class AcceptEstimateObserver
{
    public function saving(AcceptEstimate $accept)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $accept->company_id = company()->id;
        }
    }
}
