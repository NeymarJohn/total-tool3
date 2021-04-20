<?php

namespace App\Events;

use App\Leave;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $leave;
    public $status;
    public $multiDates;

    public function __construct(Leave $leave, $status, $multiDates = null)
    {
        $this->leave = $leave;
        $this->status = $status;
        $this->multiDates = $multiDates;
    }

}
