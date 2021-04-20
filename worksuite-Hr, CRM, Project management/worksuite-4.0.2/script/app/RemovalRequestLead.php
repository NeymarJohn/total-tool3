<?php

namespace App;

use App\Observers\RemovalRequestLeadObserver;
use Illuminate\Database\Eloquent\Model;

class RemovalRequestLead extends BaseModel
{

    protected $table = 'removal_requests_lead';

    protected static function boot()
    {
        parent::boot();
        static::observe(RemovalRequestLeadObserver::class);
    }

    public function lead(){
        return $this->belongsTo(Lead::class);
    }
}
