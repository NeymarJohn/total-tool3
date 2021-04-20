<?php

namespace App\Observers;

use App\Role;

class RoleObserver
{

    public function saving(Role $role)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $role->company_id = company()->id;
        }
    }

}
