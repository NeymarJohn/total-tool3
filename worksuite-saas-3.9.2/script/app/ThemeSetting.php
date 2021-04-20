<?php

namespace App;

use App\Observers\ThemeSettingObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(ThemeSettingObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
