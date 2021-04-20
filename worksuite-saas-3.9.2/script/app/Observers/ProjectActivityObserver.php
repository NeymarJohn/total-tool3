<?php

namespace App\Observers;

use App\ProjectActivity;

class ProjectActivityObserver
{

    public function saving(ProjectActivity $activity)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $activity->company_id = company()->id;
        }
    }

}
