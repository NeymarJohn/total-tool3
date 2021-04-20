<?php

namespace App\Events;

use App\Estimate;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewEstimateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $estimate;

    public function __construct(Estimate $estimate)
    {
        $this->estimate = $estimate;
    }

}
