<?php

namespace App\Listeners;

use App\Events\TaskReminderEvent;
use App\Notifications\TaskReminder;
use Illuminate\Support\Facades\Notification;

class TaskReminderListener
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
     * @param TaskReminderEvent $event
     * @return void
     */
    public function handle(TaskReminderEvent $event)
    {
        Notification::send($event->task->users, new TaskReminder($event->task));
    }
}
