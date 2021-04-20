<?php

namespace App;

use App\Observers\RemovalRequestObserver;
use Illuminate\Database\Eloquent\Model;

class RemovalRequest extends BaseModel
{
    protected static function boot()
    {
        parent::boot();
        static::observe(RemovalRequestObserver::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
