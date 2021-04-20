<?php

namespace App\Observers;

use App\ContractRenew;

class ContractRenewObserver
{
    public function saving(ContractRenew $renew)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $renew->company_id = company()->id;
        }
    }
}
