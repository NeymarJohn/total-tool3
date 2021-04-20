<?php

namespace App\Observers;

use App\Http\Controllers\Admin\AdminBaseController;
use App\ProjectUserNotes;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Notification;

class ProjectUserNotesObserver
{
    /**
     * Handle the notice "saving" event.
     *
     * @param  \App\Notice  $notice
     * @return void
     */
    public function saving(ProjectUserNotes $notes)
    {
        if (company()) {
            $notes->company_id = company()->id;
        }
    }

    

    

   
}
