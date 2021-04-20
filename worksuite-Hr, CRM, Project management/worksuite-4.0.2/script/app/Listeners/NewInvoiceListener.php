<?php

namespace App\Listeners;

use App\Events\NewInvoiceEvent;
use App\Notifications\NewInvoice;
use App\Invoice;
use Illuminate\Support\Facades\Notification;

class NewInvoiceListener
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
     * @param  NewInvoiceEvent  $event
     * @return void
     */
    public function handle(NewInvoiceEvent $event)
    {
        Notification::send($event->notifyUser, new NewInvoice($event->invoice));
    }
}
