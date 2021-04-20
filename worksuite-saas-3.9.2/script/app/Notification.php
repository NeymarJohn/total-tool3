<?php

namespace App;

use App\Observers\NotificationObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class Notification extends BaseModel
{
    protected $guarded = ['id'];
    
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }
}
