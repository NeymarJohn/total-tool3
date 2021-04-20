<?php

namespace App\Listeners;

use App\Events\NewNoticeEvent;
use App\Notifications\NewNotice;
use App\Notifications\NoticeUpdate;
use Illuminate\Support\Facades\Notification;

class NewNoticeListener
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
     * @param  NewNoticeEvent $event
     * @return void
     */
    public function handle(NewNoticeEvent $event)
    {
        if (isset($event->action) && $event->action == 'update') {
            Notification::send($event->notifyUser, new NoticeUpdate($event->notice));
        } else {
            Notification::send($event->notifyUser, new NewNotice($event->notice));
        }
    }
}
