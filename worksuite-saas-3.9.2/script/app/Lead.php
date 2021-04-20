<?php

namespace App;

use App\Observers\LeadObserver;
use App\Scopes\CompanyScope;
use App\Traits\CustomFieldsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Lead extends BaseModel
{
    use Notifiable;
    use CustomFieldsTrait;

    protected $table = 'leads';
    protected $appends = ['email'];

    protected static function boot()
    {
        parent::boot();

        static::observe(LeadObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function lead_source()
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function lead_agent()
    {
        return $this->belongsTo(LeadAgent::class, 'agent_id');
    }

    public function lead_status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }

    public function follow()
    {
        return $this->hasMany(LeadFollowUp::class);
    }

    public function files()
    {
        return $this->hasMany(LeadFiles::class);
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

    public function getEmailAttribute($value)
    {
        return $this->client_email;
    }
    public function getNameAttribute($value)
    {
        return $this->client_name;
    }

    public function routeNotificationForMail()
    {
        return $this->client_email;
    }
}
