<?php

namespace App\Observers;

use App\Estimate;
use App\TicketReplyTemplate;

class TicketReplyTemplateObserver
{

    public function saving(TicketReplyTemplate $replyTemplate)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $replyTemplate->company_id = company()->id;
        }
    }

}
