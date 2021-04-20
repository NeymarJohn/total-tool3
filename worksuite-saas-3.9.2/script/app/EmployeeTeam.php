<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeTeam extends BaseModel
{
    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }
}
