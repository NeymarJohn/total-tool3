<?php

namespace App\Listeners;

use App\Events\NewExpenseEvent;
use App\Notifications\NewExpenseAdmin;
use App\Notifications\NewExpenseMember;
use App\Notifications\NewExpenseStatus;
use App\User;
use Illuminate\Support\Facades\Notification;

class NewExpenseListener
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
     * @param  NewExpenseEvent  $event
     * @return void
     */
    public function handle(NewExpenseEvent $event)
    {
        if ($event->status == 'admin') {
            Notification::send($event->expense->user, new NewExpenseMember($event->expense));
        } elseif ($event->status == 'member') {
            Notification::send(User::allAdmins(), new NewExpenseAdmin($event->expense));
        } elseif ($event->status == 'status') {
            Notification::send($event->expense->user, new NewExpenseStatus($event->expense));
        }
    }
}
