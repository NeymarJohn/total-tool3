<?php

namespace App\Events;

use App\Notice;
use App\Payment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewPaymentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $notifyUser;

    public function __construct(Payment $payment, $notifyUser)
    {
        $this->payment = $payment;
        $this->notifyUser = $notifyUser;
    }

}
