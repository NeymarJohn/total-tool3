<?php

namespace App\Observers;

use App\ClientPayment;
use App\Events\InvoicePaymentReceivedEvent;

class InvoicePaymentReceivedObserver
{
    public function created(ClientPayment $payment)
    {
        try{
            if (!isRunningInConsoleOrSeeding() ) {
                if($payment->invoice){
                    event(new InvoicePaymentReceivedEvent($payment->invoice));
                }
            }
        }catch (\Exception $e){

        }

    }
}
