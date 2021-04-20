<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SlackSetting extends BaseModel
{

    protected $appends = ['slack_logo_url'];

    public function getSlackLogoUrlAttribute()
    {
        if (is_null($this->slack_logo)) {
            return "http://via.placeholder.com/200x150.png?text=" . __('modules.slackSettings.uploadSlackLogo');
        }
        return asset_url('slack-logo/' . $this->slack_logo);
    }

    public static function setting()
    {
        return cache()->remember(
            'slack-setting', 60*60*24, function () {
                return SlackSetting::first();
        });
    }
}
