<?php

namespace App;

use App\Observers\ProjectSettingObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProjectSetting extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectSettingObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function getRemindToAttribute($value)
    {
        return json_decode($value);
    }

    public function setRemindToAttribute($value)
    {
        $this->attributes['remind_to'] = json_encode($value);
    }
}
