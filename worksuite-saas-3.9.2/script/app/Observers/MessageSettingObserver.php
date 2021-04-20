<?php

namespace App\Observers;

use App\MessageSetting;

class MessageSettingObserver
{

    public function saving(MessageSetting $message)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $message->company_id = company()->id;
        }
    }

}
