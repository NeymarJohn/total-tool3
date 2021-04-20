<?php

namespace App\Observers;

use App\EmployeeDetails;
use App\EmployeeLeaveQuota;
use App\Events\LeaveEvent;
use App\Leave;
use App\LeaveType;
use App\User;

class LeaveTypeObserver
{
    
    public function created(LeaveType $leaveType)
    {
        if (!isRunningInConsoleOrSeeding() && request()->has('all_employees')) {
            $employees = EmployeeDetails::select('id','user_id')->get();

            foreach ($employees as $key => $employee) {
                EmployeeLeaveQuota::create(
                    [
                        'user_id' => $employee->user_id,
                        'leave_type_id' => $leaveType->id,
                        'no_of_leaves' => $leaveType->no_of_leaves
                    ]
                );
            }
        }
    }

}
