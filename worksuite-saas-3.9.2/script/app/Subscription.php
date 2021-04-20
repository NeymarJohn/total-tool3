<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends BaseModel
{
    protected $dates = ['created_at'];

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
