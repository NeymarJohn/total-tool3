<?php

namespace App;

use App\Observers\UserActivityObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(UserActivityObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }
}
