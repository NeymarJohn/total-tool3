<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushNotificationSetting extends BaseModel
{
    protected $appends = ['notification_logo_url'];

    public function getNotificationLogoUrlAttribute()
    {
        if (is_null($this->notification_logo)) {
            return "http://via.placeholder.com/200x150.png?text=".__('modules.slackSettings.uploadSlackLogo');
        }
        return asset_url('notification-logo/'.$this->notification_logo);
    }
}
