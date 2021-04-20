<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketGroup extends BaseModel
{
    public function agents()
    {
        return $this->hasMany(TicketAgentGroups::class, 'group_id');
    }


    public function enabled_agents()
    {
        return $this->agents()->where('status', '=', 'enabled');
    }
}
