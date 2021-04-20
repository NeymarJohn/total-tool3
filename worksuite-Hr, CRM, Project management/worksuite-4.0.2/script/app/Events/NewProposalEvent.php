<?php

namespace App\Events;

use App\Invoice;
use App\Proposal;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewProposalEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $proposal;
    public $type;

    public function __construct(Proposal $proposal, $type)
    {
        $this->proposal = $proposal;
        $this->type = $type;
    }

}
