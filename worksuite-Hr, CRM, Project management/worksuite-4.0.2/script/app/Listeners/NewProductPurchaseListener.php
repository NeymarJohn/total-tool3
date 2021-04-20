<?php

namespace App\Listeners;

use App\Events\NewProductPurchaseEvent;
use App\Notifications\NewProductPurchaseRequest;
use App\User;
use Illuminate\Support\Facades\Notification;

class NewProductPurchaseListener
{

    /**
     * NewProductPurchaseListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param NewProductPurchaseEvent $event
     */
    public function handle(NewProductPurchaseEvent $event)
    {
        $admins = User::allAdmins();
        Notification::send($admins, new NewProductPurchaseRequest($event->invoice));
    }
}
