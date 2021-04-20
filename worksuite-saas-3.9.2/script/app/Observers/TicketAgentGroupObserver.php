<?php

namespace App\Observers;

use App\Estimate;
use App\TicketAgentGroups;

class TicketAgentGroupObserver
{

    public function saving(TicketAgentGroups $agentGroup)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $agentGroup->company_id = company()->id;
        }
    }

}
