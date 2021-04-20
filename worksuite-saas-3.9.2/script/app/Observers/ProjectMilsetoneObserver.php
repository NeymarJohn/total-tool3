<?php

namespace App\Observers;

use App\ProjectMilestone;

class ProjectMilsetoneObserver
{

    public function saving(ProjectMilestone $milestone)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $milestone->company_id = company()->id;
        }
    }

}
