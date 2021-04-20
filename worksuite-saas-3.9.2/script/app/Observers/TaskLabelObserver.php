<?php

namespace App\Observers;

use App\TaskLabelList;

class TaskLabelObserver
{

    public function saving(TaskLabelList $lead)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $lead->company_id = company()->id;
        }
    }

}
