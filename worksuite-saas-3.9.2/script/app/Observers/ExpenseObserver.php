<?php

namespace App\Observers;

use App\Events\NewExpenseEvent;
use App\Expense;
use Illuminate\Support\Facades\File;

class ExpenseObserver
{

    public function created(Expense $expense)
    {
        if (!isRunningInConsoleOrSeeding()) {
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

    public function saving(Expense $expense)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $expense->company_id = company()->id;
        }
    }

    public function deleting(Expense $expense)
    {
        File::delete('user-uploads/expense-invoice/' . $expense->bill);
    }

    public function updating(Expense $expense)
    {
        $original = $expense->getOriginal();
        if ($expense->isDirty('bill')) {
            File::delete('user-uploads/expense-invoice/' . $original['bill']);
        }
    }
}
