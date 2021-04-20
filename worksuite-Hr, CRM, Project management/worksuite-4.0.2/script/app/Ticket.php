<?php

namespace App;

use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $appends = ['created_on'];

    protected static function boot()
    {
        parent::boot();

        static::observe(TicketObserver::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id')->withoutGlobalScopes(['active']);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function reply()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }

    public function tags()
    {
        return $this->hasMany(TicketTag::class, 'ticket_id');
    }
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
    public function getCreatedOnAttribute()
    {
        $setting = cache()->remember(
            'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
        );
        if (!is_null($this->created_at)) {
            return $this->created_at->timezone($setting->timezone)->format('d M Y H:i');
        }
        return "";
    }
}
