<?php

namespace App\Observers;

use App\TicketGroup;

class TicketGroupObserver
{

    public function saving(TicketGroup $group)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $group->company_id = company()->id;
        }
    }

}
