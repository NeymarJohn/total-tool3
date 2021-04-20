<?php

namespace App\Listeners;

use App\Events\NewChatEvent;
use App\Notifications\LeaveApplication;
use App\Notifications\LeaveStatusApprove;
use App\Notifications\LeaveStatusReject;
use App\Notifications\LeaveStatusUpdate;
use App\Notifications\NewChat;
use App\Notifications\NewLeaveRequest;
use App\User;
use Illuminate\Support\Facades\Notification;

class NewChatListener
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
     * @param  NewChatEvent $event
     * @return void
     */
    public function handle(NewChatEvent $event)
    {
        $notifyUser = User::withoutGlobalScope('active')->findOrFail($event->userChat->user_id);
        Notification::send($notifyUser, new NewChat($event->userChat));
    }
}
