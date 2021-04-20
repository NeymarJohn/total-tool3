<?php

namespace App;

use App\Observers\EmployeeFaqFileObserver;
use App\Observers\FaqFileObserver;
use App\Scopes\CompanyScope;

class EmployeeFaqFile extends BaseModel
{

    protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {
        return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('employee-faq-files/'.$this->employee_faq_id.'/'.$this->hashname);
    }

    protected static function boot()
    {
        parent::boot();

        static::observe(EmployeeFaqFileObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
