<?php

namespace App\Listeners;

use App\Events\ProjectReminderEvent;
use App\Notifications\ProjectReminder;
use Illuminate\Support\Facades\Notification;

class ProjectReminderListener
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
     * @param ProjectReminderEvent $event
     * @return void
     */
    public function handle(ProjectReminderEvent $event)
    {
        Notification::send($event->user, new ProjectReminder($event->projects, $event->data));
    }
}
