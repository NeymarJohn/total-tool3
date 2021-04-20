<?php

namespace App\Observers;


use App\ClientDetails;
use App\CreditNotes;
use App\Events\NewCreditNoteEvent;
use App\UniversalSearch;
use App\User;

class CreditNoteObserver
{

    public function saving(CreditNotes $creditNote)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $creditNote->company_id = company()->id;
        }
    }

    public function created(CreditNotes $creditNote){
        if (!isRunningInConsoleOrSeeding()) {
            $clientId = null;

            if($creditNote->client_id){
                $clientId = $creditNote->client_id;
            }
            elseif($creditNote->invoice && $creditNote->invoice->client_id != null){
                $clientId = $creditNote->invoice->client_id;
            }elseif($creditNote->project && $creditNote->project->client_id != null){
                $clientId = $creditNote->project->client_id;
            }elseif($creditNote->invoice->project && $creditNote->invoice->project->client_id != null){
                $clientId = $creditNote->invoice->project->client_id;
            }

            if ($clientId) {
                $notifyUser = ClientDetails::where('user_id', $clientId)->first();
//                 Notify client
                if($notifyUser){
                    event(new NewCreditNoteEvent($creditNote, $notifyUser));
                }
            }
        }
    }

    public function deleting(CreditNotes $creditNote){
        $universalSearches = UniversalSearch::where('searchable_id', $creditNote->id)->where('module_type', 'creditNote')->get();
        if ($universalSearches){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }

}
