<?php

namespace App\Events;

use App\ProjectRating;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rating;
    public $type;
    public $multiDates;

    public function __construct(ProjectRating $rating, $type)
    {
        $this->rating = $rating;
        $this->type = $type;
    }

}
