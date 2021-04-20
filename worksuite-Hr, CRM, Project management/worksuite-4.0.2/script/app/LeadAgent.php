<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadAgent extends BaseModel
{
    protected $table = 'lead_agents';

    public function user()
    {
        return $this->belongsTo(User::class)->withoutGlobalScopes(['active']);
    }
}
