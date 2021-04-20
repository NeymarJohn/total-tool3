<?php

namespace App\Events;

use App\Expense;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewExpenseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $expense;
    public $status;

    public function __construct(Expense $expense, $status)
    {
        $this->expense = $expense;
        $this->status = $status;
    }

}
