<?php

namespace App\Listeners;

use App\Events\NewInvoiceEvent;
use App\Events\NewProposalEvent;
use App\Lead;
use App\Notifications\NewInvoice;
use App\Invoice;
use App\Notifications\NewProposal;
use App\Notifications\ProposalSigned;
use App\User;
use Illuminate\Support\Facades\Notification;

class NewProposalListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewInvoiceEvent  $event
     * @return void
     */
    public function handle(NewProposalEvent $event)
    {
//        Notification::send($event->notifyUser, new NewProposal($event->proposal));

        if($event->type == 'statusUpdate'){
            $allAdmins = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.*')
                ->where('roles.name', 'admin')->get();
            // Notify admins
            Notification::send($allAdmins, new ProposalSigned($event->proposal));
        }
        else{
            // Notify client
            $notifyUser = Lead::where('id',$event->proposal->lead_id)->get();

            Notification::send($notifyUser, new NewProposal($event->proposal));
        }
    }
}
