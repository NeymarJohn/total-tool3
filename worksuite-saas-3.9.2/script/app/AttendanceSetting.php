<?php

namespace App;

use App\Observers\AttendanceSettingObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends BaseModel
{

    protected static function boot()
    {
        parent::boot();

        static::observe(AttendanceSettingObserver::class);

        static::addGlobalScope(new CompanyScope);

    }
}
