<?php

namespace App\Events;

use App\Invoice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewInvoiceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoice;
    public $notifyUser;

    public function __construct(Invoice $invoice, $notifyUser)
    {
        $this->invoice = $invoice;
        $this->notifyUser = $notifyUser;
    }

}
