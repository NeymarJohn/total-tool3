<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadFollowUp extends BaseModel
{
    protected $table = 'lead_follow_up';
    protected $dates = ['next_follow_up_date', 'created_at'];

    public function lead(){
        return $this->belongsTo(Lead::class);
    }
}
