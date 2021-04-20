<?php

namespace App\Observers;

use App\EmployeeLeaveQuota;
use App\LeaveType;
use App\EmployeeDetails;

class EmployeeDetailsObserver
{
    public function created(EmployeeDetails $detail)
    {
        $leaveTypes = LeaveType::get();
        foreach ($leaveTypes as $key => $value) {
            EmployeeLeaveQuota::create(
                [
                    'user_id' => $detail->user_id,
                    'leave_type_id' => $value->id,
                    'no_of_leaves' => $value->no_of_leaves
                ]
            );
        }
    }
}
