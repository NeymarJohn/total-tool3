<?php

namespace App\Observers;


use App\TicketFile;

class TicketFileObserver
{

    public function saving(TicketFile $file)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $file->company_id = company()->id;
        }
    }

}
