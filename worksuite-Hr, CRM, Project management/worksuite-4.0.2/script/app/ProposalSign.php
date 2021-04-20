<?php

namespace App;

use App\Observers\ProposalSignObserver;
use App\Scopes\CompanyScope;

class ProposalSign extends BaseModel
{
    public function getSignatureAttribute()
    {
        return asset_url('proposal/sign/'.$this->attributes['signature']);
    }
}
