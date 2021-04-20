<?php

namespace App\Observers;

use App\TicketChannel;

class TicketChannelObserver
{

    public function saving(TicketChannel $channel)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $channel->company_id = company()->id;
        }
    }

}
