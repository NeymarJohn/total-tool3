<?php

namespace App;

use App\Observers\SlackSettingObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SlackSetting extends BaseModel
{
    protected $appends = 'slack_logo_url';

    protected static function boot()
    {
        parent::boot();

        static::observe(SlackSettingObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function getSlackLogoUrlAttribute()
    {
        return ($this->slack_logo) ? asset_url('slack-logo/' . $this->slack_logo) : 'https://via.placeholder.com/200x150.png?text='.str_replace(' ', '+', __('modules.slackSettings.uploadSlackLog'));
    }

}
