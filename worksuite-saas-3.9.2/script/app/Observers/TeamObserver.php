<?php

namespace App\Observers;

use App\Team;
use App\User;

class TeamObserver
{

    public function saving(Team $team)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $team->company_id = company()->id;
        }
    }

}
