<?php

namespace App\Observers;

use App\Contract;
use App\Events\NewContractEvent;

class ContractObserver
{

    // Notify client when new contract is created
    public function created(Contract $contract){
        if (!isRunningInConsoleOrSeeding() ){
            event(new NewContractEvent($contract));
        }
    }

}
