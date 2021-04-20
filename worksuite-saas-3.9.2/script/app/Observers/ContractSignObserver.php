<?php

namespace App\Observers;

use App\ContractSign;

class ContractSignObserver
{
    public function saving(ContractSign $sign)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $sign->company_id = company()->id;
        }
    }
}
