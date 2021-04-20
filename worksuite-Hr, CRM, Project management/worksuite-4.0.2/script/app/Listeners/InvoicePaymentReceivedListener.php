<?php

namespace App\Listeners;

use App\Events\InvoicePaymentReceivedEvent;
use App\Notifications\InvoicePaymentReceived;
use App\User;
use Illuminate\Support\Facades\Notification;

class InvoicePaymentReceivedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InvoicePaymentReceivedEvent  $event
     * @return void
     */
    public function handle(InvoicePaymentReceivedEvent $event)
    {
        Notification::send(User::allAdmins(), new InvoicePaymentReceived($event->paymentInvoice));
    }
}
