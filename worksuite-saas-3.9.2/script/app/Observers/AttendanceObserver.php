<?php

namespace App\Observers;

use App\Attendance;

class AttendanceObserver
{

    public function saving(Attendance $attendance)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $attendance->company_id = company()->id;
        }
    }

}
