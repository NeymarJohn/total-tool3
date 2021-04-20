<?php

namespace App\Observers;

use App\TicketType;

class TicketTypeObserver
{

    public function saving(TicketType $type)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $type->company_id = company()->id;
        }
    }

}
