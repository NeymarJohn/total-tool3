<?php

namespace App;

use App\Observers\ContractFileObserver;
use App\Scopes\CompanyScope;

class ContractFile extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {
        return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('contract-files/'.$this->contract_id.'/'.$this->hashname);
    }

    protected static function boot()
    {
        parent::boot();

        static::observe(ContractFileObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
