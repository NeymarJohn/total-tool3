<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends BaseModel
{
    protected $table = 'role_user';

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }
    public function role(){
        return $this->belongsTo(Role::class, 'user_id');
    }

    public $timestamps = false;
}
