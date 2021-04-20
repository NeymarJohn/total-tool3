<?php

namespace App;

use App\Observers\EmailNotificationSettingObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EmailNotificationSetting extends BaseModel
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::observe(EmailNotificationSettingObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
