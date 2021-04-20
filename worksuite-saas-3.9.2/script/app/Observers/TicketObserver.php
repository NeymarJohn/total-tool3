<?php

namespace App\Observers;

use App\Company;
use App\Events\TicketRequesterEvent;
use App\Notifications\NewTicket;
use App\Notifications\TicketAgent;
use App\Ticket;
use App\UniversalSearch;
use App\User;
use Illuminate\Support\Facades\Notification;

class TicketObserver
{

    public function created(Ticket $ticket)
    {
        if (!isRunningInConsoleOrSeeding()) {
            //send admin notification
            $company = Company::find($ticket->company_id);
            if($company)
            {
                $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('users.id', 'users.name', 'users.email_notifications', 'users.email', 'users.created_at', 'users.image')
                    ->where('roles.name', 'admin')
                    ->where('users.company_id', $ticket->company_id)
                    ->get();

                Notification::send($users, new NewTicket($ticket));

                if($ticket->requester){
                    event(new TicketRequesterEvent($ticket, $ticket->requester));
                }
            }

        }
    }

    public function saving(Ticket $ticket)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $ticket->company_id = company()->id;
        }
    }

    public function updated(Ticket $ticket)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($ticket->isDirty('agent_id')) {
                Notification::send($ticket->agent, new TicketAgent($ticket));
            }
        }
    }

    public function deleting(Ticket $ticket){
        $universalSearches = UniversalSearch::where('searchable_id', $ticket->id)->where('module_type', 'ticket')->get();
        if ($universalSearches){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }

}
