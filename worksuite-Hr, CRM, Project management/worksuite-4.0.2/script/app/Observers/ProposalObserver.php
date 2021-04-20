<?php

namespace App\Observers;

use App\Events\LeadEvent;
use App\Events\NewProposalEvent;
use App\Lead;
use App\Notifications\NewProposal;
use App\Proposal;
use App\UniversalSearch;
use App\User;
use Illuminate\Support\Facades\Notification;

class ProposalObserver
{

//    public function created(Proposal $proposal)
//    {
//        if (!isRunningInConsoleOrSeeding()) {
//            if (($proposal->lead->client_email)) {
//                // Notify client
//                    Notification::route('mail', $proposal->lead->client_email)
//                        ->notify(new NewProposal($proposal));
//            }
//        }
//    }

    /**
     * @param Proposal $proposal
     */
    public function created(Proposal $proposal)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $type = 'new';
            event(new NewProposalEvent($proposal, $type));
        }
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
