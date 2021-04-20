<?php

namespace App\Observers;

use App\Events\LeaveEvent;
use App\Leave;
use App\LeaveType;

class LeaveObserver
{
    public function creating(Leave $leave)
    {
        $leaveTypes = LeaveType::where('id', $leave->leave_type_id)->first();
        $leave->paid = $leaveTypes->paid;   

    }
    public function created(Leave $leave)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (request()->duration == 'multiple') {
                if (session()->has('leaves_duration')) {
                    event(new LeaveEvent($leave, 'created', request()->multi_date));
                }
            } else {
                event(new LeaveEvent($leave, 'created'));
            }
        }
    }
    public function updating(Leave $leave)
    {       
        $leaveTypes = LeaveType::where('id', $leave->leave_type_id)->first();
        $leave->paid = $leaveTypes->paid;
    }
    public function updated(Leave $leave)
    {
       
        if (!isRunningInConsoleOrSeeding()) {
            // Send from ManageLeavesController
            if ($leave->isDirty('status')) {
                event(new LeaveEvent($leave, 'statusUpdated'));
            } else {
                
                event(new LeaveEvent($leave, 'updated'));
            }
        }
    }
}
