<?php

namespace App\Observers;

use App\Events\NewExpenseEvent;
use App\Expense;

class ExpenseObserver
{
    public function created(Expense $expense)
    {
        $userType = '';
        if (!isRunningInConsoleOrSeeding() ) {
            // Default status is approved means it is posted by admin
            if ($expense->status == 'approved') {
                $userType = 'admin';
            }

            // Default status is pending that mean it is posted by member
            if ($expense->status == 'pending') {
                $userType = 'member';
            }
            event(new NewExpenseEvent($expense, $userType));
        }
    }

    public function updated(Expense $expense)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($expense->isDirty('status')) {
                event(new NewExpenseEvent($expense, 'status'));
            }

        }
    }
}
