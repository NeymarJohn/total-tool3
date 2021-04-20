<?php

namespace App;

use App\Observers\DiscussionCategoryObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class DiscussionCategory extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::observe(DiscussionCategoryObserver::class);
        static::addGlobalScope(new CompanyScope);
    }

    protected $guarded = ['id'];
}
