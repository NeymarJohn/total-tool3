<?php

namespace App;

use App\Observers\LeadObserver;
use App\Traits\CustomFieldsTrait;
use Illuminate\Notifications\Notifiable;

class Lead extends BaseModel
{
    use Notifiable;
    use CustomFieldsTrait;

    protected $table = 'leads';

    protected static function boot()
    {
        parent::boot();

        static::observe(LeadObserver::class);
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->client_email;
    }
    public function lead_agent()
    {
        return $this->belongsTo(LeadAgent::class, 'agent_id');
    }
    public function lead_source()
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }
    public function lead_status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }
    public function category()
    {
        return $this->belongsTo(LeadCategory::class, 'category_id');
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function follow()
    {
        return $this->hasMany(LeadFollowUp::class);
    }
    public function followup()
    {
        return $this->hasOne(LeadFollowUp::class, 'lead_id')->orderBy('created_at', 'desc');
    }
    public function files()
    {
        return $this->hasMany(LeadFiles::class);
    }
}
