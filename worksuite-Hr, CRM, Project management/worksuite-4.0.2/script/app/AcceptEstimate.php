<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcceptEstimate extends BaseModel
{
    public function getSignatureAttribute()
    {
        return asset_url('estimate/accept/'.$this->attributes['signature']);
    }
}
