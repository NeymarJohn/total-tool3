<?php

namespace App\Observers;

use App\NoticeView;

class NoticeViewObserver
{
    /**
     * Handle the notice "saving" event.
     *
     * @param  \App\Notice  $notice
     * @return void
     */
    public function saving(NoticeView $notice)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $notice->company_id = company()->id;
        }
    }
}
