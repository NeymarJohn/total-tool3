<?php

namespace App\Observers;

use App\Events\NewProposalEvent;
use App\Proposal;


class ProposalObserver
{

    public function saving(Proposal $proposal)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $proposal->company_id = company()->id;
        }
    }

    /**
     * @param Proposal $proposal
     */
    public function created(Proposal $proposal)
    {
       $type = 'new';
       event(new NewProposalEvent($proposal, $type));
    }

    /** @param Proposal $proposal
     */
    public function updated(Proposal $proposal)
    {
        if ($proposal->isDirty('status')) {
            $type = 'statusUpdate';
            event(new NewProposalEvent($proposal, $type));
        }
    }
}
