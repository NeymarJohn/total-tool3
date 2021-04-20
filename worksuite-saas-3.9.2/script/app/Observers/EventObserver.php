<?php

namespace App\Observers;

use App\Event;
use App\Expense;

class EventObserver
{

    public function saving(Event $event)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $event->company_id = company()->id;
        }
    }

}
