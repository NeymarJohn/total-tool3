<?php

namespace App;

use App\Observers\FaqFileObserver;
use App\Scopes\CompanyScope;

class FaqFile extends BaseModel
{

    protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {
        return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('faq-files/'.$this->faq_id.'/'.$this->hashname);
    }

    protected static function boot()
    {
        parent::boot();

        static::observe(FaqFileObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
