<?php

namespace App;

use App\Observers\EventObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Event extends BaseModel
{
    protected $dates = ['start_date_time', 'end_date_time'];

    protected static function boot()
    {
        parent::boot();

        static::observe(EventObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function attendee(){
        return $this->hasMany(EventAttendee::class, 'event_id');
    }
    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');

    }
    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');

    }
}
