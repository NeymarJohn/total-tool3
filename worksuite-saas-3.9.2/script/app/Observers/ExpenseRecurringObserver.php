<?php

namespace App\Observers;

use App\Events\NewExpenseRecurringEvent;
use App\Expense;
use App\ExpenseRecurring;
use Illuminate\Support\Facades\File;

class ExpenseRecurringObserver
{

    public function created(ExpenseRecurring $expense)
    {
        if (!isRunningInConsoleOrSeeding()) {
            // Default status is approved means it is posted by admin
            $userType = '';
            if (!isRunningInConsoleOrSeeding() ) {
                event(new NewExpenseRecurringEvent($expense, $userType));
            }
        }
    }

    public function updated(ExpenseRecurring $expense)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($expense->isDirty('status')) {
                $userType = 'status';
                event(new NewExpenseRecurringEvent($expense, $userType));
            }
        }
    }

    public function saving(ExpenseRecurring $expense)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $expense->company_id = company()->id;
        }
    }

    public function deleting(ExpenseRecurring $expense)
    {
        File::delete('user-uploads/expense-invoice/' . $expense->bill);
    }

    public function updating(ExpenseRecurring $expense)
    {
        $original = $expense->getOriginal();
        if ($expense->isDirty('bill')) {
            File::delete('user-uploads/expense-invoice/' . $original['bill']);
        }
    }
}
