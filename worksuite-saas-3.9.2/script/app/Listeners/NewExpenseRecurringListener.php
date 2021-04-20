<?php

namespace App\Listeners;

use App\Events\NewExpenseEvent;
use App\Events\NewExpenseRecurringEvent;
use App\Notifications\ExpenseRecurringStatus;
use App\Notifications\NewExpenseAdmin;
use App\Notifications\NewExpenseMember;
use App\Notifications\NewExpenseRecurringMember;
use App\Notifications\NewExpenseStatus;
use App\User;
use Illuminate\Support\Facades\Notification;

class NewExpenseRecurringListener
{
    /**
     * NewExpenseRecurringListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewExpenseRecurringEvent  $event
     * @return void
     */
    public function handle(NewExpenseRecurringEvent $event)
    {
        if ($event->status == 'status') {
            Notification::send($event->expense->user, new ExpenseRecurringStatus($event->expense));
        }
        else{
            Notification::send($event->expense->user, new NewExpenseRecurringMember($event->expense));
        }
    }
}
