<?php

namespace App\Events;

use App\Company;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompanyUrlEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $company;
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * @return PrivateChannel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('forgot-company');
    }
}
