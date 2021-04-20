<?php

namespace App\Events;

use App\Invoice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoicePaymentReceivedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $paymentInvoice;

    public function __construct(Invoice $paymentInvoice)
    {
        $this->paymentInvoice = $paymentInvoice;
    }

}
