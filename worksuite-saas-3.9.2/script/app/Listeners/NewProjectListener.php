<?php

namespace App\Listeners;

use App\Events\NewExpenseEvent;
use App\Events\NewExpenseRecurringEvent;
use App\Events\NewInvoiceRecurringEvent;
use App\Events\NewProjectEvent;
use App\Notifications\ExpenseRecurringStatus;
use App\Notifications\InvoiceRecurringStatus;
use App\Notifications\NewExpenseAdmin;
use App\Notifications\NewExpenseMember;
use App\Notifications\NewExpenseRecurringMember;
use App\Notifications\NewExpenseStatus;
use App\Notifications\NewProject;
use App\Notifications\NewRecurringInvoice;
use App\Scopes\CompanyScope;
use App\User;
use Illuminate\Support\Facades\Notification;

class NewProjectListener
{
    /**
     * NewExpenseRecurringListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * @param NewProjectEvent $event
     */
    public function handle(NewProjectEvent $event)
    {
        if (($event->project->client_id != null)) {
            $clientId =  $event->project->client_id;
            // Notify client
            $notifyUser = User::withoutGlobalScopes([CompanyScope::class, 'active'])->findOrFail($clientId);
            if ($notifyUser) {
                Notification::send($notifyUser, new NewProject($event->project));
            }
        }


    }
}
