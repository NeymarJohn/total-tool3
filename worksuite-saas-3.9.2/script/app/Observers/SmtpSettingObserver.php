<?php

namespace App\Observers;

use App\EmailNotificationSetting;
use App\Leave;
use App\SmtpSetting;

class SmtpSettingObserver
{
    /**
     * Handle the SmtpSetting "saving" event.
     *
     * @param  \App\SmtpSetting  $setting
     * @return void
     */
    public function saving(SmtpSetting $setting)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $setting->company_id = company()->id;
        }
    }


}
