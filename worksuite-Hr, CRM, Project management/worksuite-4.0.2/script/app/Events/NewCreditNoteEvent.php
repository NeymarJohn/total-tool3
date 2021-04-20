<?php

namespace App\Events;

use App\CreditNotes;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCreditNoteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $creditNote;
    public $notifyUser;

    public function __construct(CreditNotes $creditNote, $notifyUser)
    {
        $this->creditNote = $creditNote;
        $this->notifyUser = $notifyUser;
    }

}
