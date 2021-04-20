<?php

namespace App\Listeners;
use App\Events\NewInvoiceRecurringEvent;
use App\Events\NewProposalEvent;
use App\Lead;
use App\Notifications\NewClientProposal;
use App\Notifications\ProposalSigned;
use App\User;
use Illuminate\Support\Facades\Notification;

class NewProposalListener
{
    /**
     * NewExpenseRecurringListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * @param NewProposalEvent $event
     */
    public function handle(NewProposalEvent $event)
    {
        if($event->type == 'statusUpdate'){
            $allAdmins = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.*')
                ->where('roles.name', 'admin')
                ->where('users.company_id', $event->proposal->company_id)->get();
            // Notify admins
            Notification::send($allAdmins, new ProposalSigned($event->proposal));
        }
        else{
            // Notify client
            $notifyUser = Lead::where('id',$event->proposal->lead_id)->get();

            Notification::send($notifyUser, new NewClientProposal($event->proposal));
        }

    }
}
