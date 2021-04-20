<?php

namespace App\Observers;

use App\Contract;
use App\Notifications\NewContract;

class ContractObserver
{
    public function created(Contract $contract){
        if (!app()->runningInConsole() && !is_null($contract->client)){
            $contract->client->notify(new NewContract($contract));
        }
    }

    public function saving(Contract $contract)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $contract->company_id = company()->id;
        }
    }
}
