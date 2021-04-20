<?php

namespace App\Events;

use App\Invoice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentReminderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notifyUser;
    public $invoice;

    public function __construct(Invoice $invoice, $notifyUser)
    {
        $this->invoice = $invoice;
        $this->notifyUser = $notifyUser;
    }

}
