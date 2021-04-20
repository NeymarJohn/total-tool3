<?php

namespace App\Observers;

use App\EmployeeDetails;
use App\EmployeeLeaveQuota;
use App\LeaveType;
use App\User;

class LeaveTypeObserver
{
    /**
     * Handle the leave type "saving" event.
     *
     * @param  \App\LeaveType  $leaveType
     * @return void
     */
    public function saving(LeaveType $leaveType)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $leaveType->company_id = company()->id;
        }
    }

    public function created(LeaveType $leaveType)
    {
        if (!isRunningInConsoleOrSeeding() && request('all_employees')) {
            $employees = EmployeeDetails::all();

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
