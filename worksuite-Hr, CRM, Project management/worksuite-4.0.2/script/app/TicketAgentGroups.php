<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketAgentGroups extends BaseModel
{
    public function user(){
        return $this->belongsTo(User::class, 'agent_id')->withoutGlobalScopes(['active']);
    }

    public function group(){
        return $this->belongsTo(TicketGroup::class, 'group_id');
    }
}
