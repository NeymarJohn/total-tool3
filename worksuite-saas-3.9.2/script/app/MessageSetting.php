<?php

namespace App;

use App\Observers\MessageSettingObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MessageSetting extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(MessageSettingObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
