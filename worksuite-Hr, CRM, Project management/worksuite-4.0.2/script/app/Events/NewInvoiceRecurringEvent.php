<?php

namespace App\Events;

use App\ExpenseRecurring;
use App\RecurringInvoice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewInvoiceRecurringEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoice;
    public $status;

    public function __construct(RecurringInvoice $invoice, $status)
    {
        $this->invoice = $invoice;
        $this->status = $status;
    }

}
