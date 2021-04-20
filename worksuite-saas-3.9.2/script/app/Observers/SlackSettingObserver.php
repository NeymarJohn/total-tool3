<?php

namespace App\Observers;

use App\SlackSetting;

class SlackSettingObserver
{

    public function saving(SlackSetting $slack)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $slack->company_id = company()->id;
        }
    }

}
