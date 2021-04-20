<?php

namespace App\Observers;

use App\Events\NewExpenseRecurringEvent;
use App\ExpenseRecurring;

class ExpenseRecurringObserver
{
    public function created(ExpenseRecurring $expense)
    {
        $userType = '';
        if (!isRunningInConsoleOrSeeding() ) {
            event(new NewExpenseRecurringEvent($expense, $userType));
        }
    }

    public function updated(ExpenseRecurring $expense)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($expense->isDirty('status')) {
                event(new NewExpenseRecurringEvent($expense, 'status'));
            }

        }
    }
}
