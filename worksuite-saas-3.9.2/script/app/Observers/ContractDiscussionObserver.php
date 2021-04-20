<?php

namespace App\Observers;

use App\Contract;
use App\ContractDiscussion;
use App\Notification;
use App\Notifications\ContractComment;
use App\User;

class ContractDiscussionObserver
{
    public function saving(ContractDiscussion $discussion)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $discussion->company_id = company()->id;
        }
    }
    public function created(ContractDiscussion $contractDiscussion)
    {
        if (!isRunningInConsoleOrSeeding() && User::isClient(user()->id)) {
            $contract = Contract::findOrFail($contractDiscussion->contract_id);
            $admins = User::frontAllAdmins(company()->id);

            \Illuminate\Support\Facades\Notification::send($admins, new ContractComment($contract, $contractDiscussion->created_at));
        }
    }
}
