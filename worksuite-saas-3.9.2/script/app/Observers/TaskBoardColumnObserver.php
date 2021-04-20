<?php

namespace App\Observers;

use App\Task;
use App\TaskboardColumn;

class TaskBoardColumnObserver
{

    public function saving(TaskboardColumn $taskboard)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $taskboard->company_id = company()->id;
        }
    }

}
