<?php

namespace App\Observers;

use App\EmployeeLeaveQuota;

class EmployeeLeaveQuotaObserver
{

    public function saving(EmployeeLeaveQuota $detail)
    {
        if (company()) {
            $detail->company_id = company()->id;
        }
    }


}
