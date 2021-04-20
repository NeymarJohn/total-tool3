<?php

namespace App\Listeners;

use App\Events\NewCreditNoteEvent;
use App\Notifications\NewCreditNote;
use Illuminate\Support\Facades\Notification;

class NewCreditNoteListener
{
    /**
     * NewCreditNoteListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param NewCreditNoteEvent $event
     */
    public function handle(NewCreditNoteEvent $event)
    {
        Notification::send($event->notifyUser, new NewCreditNote($event->creditNote));
    }
}
