<?php

namespace App\Observers;


use App\ProjectCategory;
use App\TaskCategory;

class TaskCategoryObserver
{

    public function saving(TaskCategory $taskCategory)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $taskCategory->company_id = company()->id;
        }
    }

}
