<?php

namespace App;

use App\Observers\TicketGroupObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TicketGroup extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(TicketGroupObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function agents(){
        return $this->hasMany(TicketAgentGroups::class, 'group_id');
    }


    public function enabled_agents() {
        return $this->agents()->where('status','=', 'enabled');
    }
}
