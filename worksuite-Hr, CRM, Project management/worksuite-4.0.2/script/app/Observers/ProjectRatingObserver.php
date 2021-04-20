<?php

namespace App\Observers;


use App\Events\RatingEvent;
use App\ProjectRating;

class ProjectRatingObserver
{

    public function created(ProjectRating $rating)
    {
        if (!isRunningInConsoleOrSeeding()) {
            //Send notification to user
            event(new RatingEvent($rating, 'add'));
        }
    }

    public function updating(ProjectRating $rating)
    {
        //Send notification to user
//            event(new RatingEvent($rating, 'update'));

    }

    public function deleting(ProjectRating $rating)
    {
        if (!isRunningInConsoleOrSeeding()) {
            //Send notification to user
            event(new RatingEvent($rating, 'update'));

        }
    }

}
