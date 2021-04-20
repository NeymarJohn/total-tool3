<?php

namespace App\Observers;


use App\Events\RatingEvent;
use App\ProjectRating;

class ProjectRatingObserver
{

    public function saving(ProjectRating $rating)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $rating->company_id = company()->id;
        }
    }

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
