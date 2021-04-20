<?php

namespace App;

use App\Observers\LeadStatusObserver;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends BaseModel
{
    protected $table = 'lead_status';

    protected static function boot()
    {
        parent::boot();
        static::observe(LeadStatusObserver::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'status_id')->orderBy('column_priority');
    }

}
