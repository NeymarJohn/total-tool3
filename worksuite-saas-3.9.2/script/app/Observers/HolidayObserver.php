<?php

namespace App\Observers;

use App\Holiday;

class HolidayObserver
{

    public function saving(Holiday $holiday)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $holiday->company_id = company()->id;
        }
    }

}
