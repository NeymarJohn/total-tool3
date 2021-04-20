<?php

namespace App\Observers;

use App\Http\Controllers\Admin\AdminBaseController;
use App\ClientUserNotes;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Notification;

class ClientUserNotesObserver
{
    /**
     * Handle the notice "saving" event.
     *
     * @param  \App\Notice  $notice
     * @return void
     */
    public function saving(ClientUserNotes $notes)
    {
        if (company()) {
            $notes->company_id = company()->id;
        }
    }

    public function created(ClientUserNotes $notes)
    {
       //
    }

    

   
}
