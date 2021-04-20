<?php

namespace App;

use App\Observers\ProposalSignObserver;
use App\Scopes\CompanyScope;

class ProposalSign extends BaseModel
{

    protected static function boot()
    {
        parent::boot();

        static::observe(ProposalSignObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
    public function getSignatureAttribute()
    {
        return asset_url('proposal/sign/'.$this->attributes['signature']);
    }
}
