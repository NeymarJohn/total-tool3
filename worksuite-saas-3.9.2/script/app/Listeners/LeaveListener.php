<?php

namespace App\Listeners;

use App\Events\LeaveEvent;
use App\Notifications\LeaveApplication;
use App\Notifications\LeaveStatusApprove;
use App\Notifications\LeaveStatusReject;
use App\Notifications\LeaveStatusUpdate;
use App\Notifications\MultipleLeaveApplication;
use App\Notifications\NewLeaveRequest;
use App\Notifications\NewMultipleLeaveRequest;
use App\User;
use Illuminate\Support\Facades\Notification;

class LeaveListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LeaveEvent $event
     * @return void
     */
    public function handle(LeaveEvent $event)
    {
        if ($event->status == 'created') {
            if (!is_null($event->multiDates)) {
                Notification::send($event->leave->user, new MultipleLeaveApplication($event->leave, $event->multiDates));
                Notification::send(User::allAdmins(), new NewMultipleLeaveRequest($event->leave, $event->multiDates));
            } else {
                Notification::send($event->leave->user, new LeaveApplication($event->leave));
                Notification::send(User::allAdmins(), new NewLeaveRequest($event->leave));
            }
        } elseif ($event->status == 'statusUpdated') {
            if ($event->leave->status == 'approved') {
                Notification::send($event->leave->user, new LeaveStatusApprove($event->leave));
            } else {
                Notification::send($event->leave->user, new LeaveStatusReject($event->leave));
            }
        } elseif ($event->status == 'updated') {
            Notification::send($event->leave->user, new LeaveStatusUpdate($event->leave));
        }
    }
}
