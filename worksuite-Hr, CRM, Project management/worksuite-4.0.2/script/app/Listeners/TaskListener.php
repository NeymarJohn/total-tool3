<?php

namespace App\Listeners;

use App\Events\TaskEvent;
use App\Notifications\NewClientTask;
use App\Notifications\NewTask;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskUpdatedClient;
use App\Notifications\TaskUpdated;
use Illuminate\Support\Facades\Notification;

class TaskListener
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
     * @param  TaskEvent $event
     * @return void
     */
    public function handle(TaskEvent $event)
    {
        if ($event->notificationName == 'NewClientTask') {
            Notification::send($event->notifyUser, new NewClientTask($event->task));
        } elseif ($event->notificationName == 'NewTask') {
            Notification::send($event->notifyUser, new NewTask($event->task));
        } elseif ($event->notificationName == 'TaskUpdated') {
            Notification::send($event->notifyUser, new TaskUpdated($event->task));
        } elseif ($event->notificationName == 'TaskCompleted') {
            Notification::send($event->notifyUser, new TaskCompleted($event->task));
        } elseif ($event->notificationName == 'TaskUpdatedClient') {
            Notification::send($event->notifyUser, new TaskUpdatedClient($event->task));
        }
    }
}
