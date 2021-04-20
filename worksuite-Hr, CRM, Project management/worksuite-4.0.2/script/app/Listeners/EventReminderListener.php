<?php

namespace App\Listeners;

use App\Events\EventReminderEvent;
use App\Notifications\EventReminder;
use Illuminate\Support\Facades\Notification;

class EventReminderListener
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
     * @param EventReminderEvent $event
     * @return void
     */
    public function handle(EventReminderEvent $event)
    {
        Notification::send($event->event->getUsers(), new EventReminder($event->event));
    }
}
