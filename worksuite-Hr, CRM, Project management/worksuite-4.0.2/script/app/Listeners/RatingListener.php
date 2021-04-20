<?php

namespace App\Listeners;

use App\Events\LeadEvent;
use App\Events\RatingEvent;
use App\Notifications\NewRating;
use App\Notifications\RatingUpdate;
use App\ProjectRating;
use App\User;
use Illuminate\Support\Facades\Notification;

class RatingListener
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
     * @param  LeadEvent $event
     * @return void
     */
    public function handle(RatingEvent $event)
    {
        $notifyUsers = User::allAdmins();

        if ($event->type == 'add') {
            Notification::send($notifyUsers, new NewRating($event->rating));
        }
        else{

            Notification::send($notifyUsers, new RatingUpdate($event->rating));
        }
    }
}
