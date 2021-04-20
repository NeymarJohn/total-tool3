<?php

namespace App\Observers;

use App\LogTimeFor;

class LogTimeForObserver
{

    public function saving(LogTimeFor $logTimeFor)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $logTimeFor->company_id = company()->id;
            session()->forget('time_log_setting');
        }
    }

}
