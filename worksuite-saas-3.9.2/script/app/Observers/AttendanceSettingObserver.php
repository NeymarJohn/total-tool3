<?php

namespace App\Observers;

use App\AttendanceSetting;

class AttendanceSettingObserver
{

    public function saving(AttendanceSetting $setting)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $setting->company_id = company()->id;
        }
    }

}
