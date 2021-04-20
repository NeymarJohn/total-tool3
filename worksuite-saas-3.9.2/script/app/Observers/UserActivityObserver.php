<?php

namespace App\Observers;

use App\Expense;
use App\UserActivity;

class UserActivityObserver
{

    public function saving(UserActivity $activity)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $activity->company_id = company()->id;
        }
    }

}
