<?php

namespace App;

use App\Observers\DiscussionCategoryObserver;
use Illuminate\Database\Eloquent\Model;

class DiscussionCategory extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::observe(DiscussionCategoryObserver::class);
    }

    protected $guarded = ['id'];
}
