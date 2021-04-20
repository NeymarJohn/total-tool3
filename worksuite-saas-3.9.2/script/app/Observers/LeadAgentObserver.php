<?php

namespace App\Observers;

use App\LeadAgent;

class LeadAgentObserver
{
    public function saving(LeadAgent $leadAgent)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $leadAgent->company_id = company()->id;
        }
    }

}
