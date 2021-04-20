<?php

namespace App\Observers;

use App\Events\LeaveEvent;
use App\Leave;

class LeaveObserver
{
    /**
     * Handle the leave "saving" event.
     *
     * @param  \App\Leave  $leave
     * @return void
     */
    public function saving(Leave $leave)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $leave->company_id = company()->id;
        }
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

    public function updated(Leave $leave)
    {
        if (!app()->runningInConsole()) {
            if ($leave->isDirty('status')) {
                event(new LeaveEvent($leave, 'statusUpdated'));
            } else {
                event(new LeaveEvent($leave, 'updated'));
            }
        }
    }

}
