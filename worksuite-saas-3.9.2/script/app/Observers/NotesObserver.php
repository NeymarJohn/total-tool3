<?php

namespace App\Observers;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Notes;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Notification;

class NotesObserver
{
    /**
     * Handle the notice "saving" event.
     *
     * @param  \App\Notice  $notice
     * @return void
     */
    public function saving(Notes $notes)
    {
        if (company()) {
            $notes->company_id = company()->id;
        }
    }

    public function created(Notes $notes)
    {
       //
    }

    

   
}
