<?php

namespace App;


use App\Observers\EmployeeFaqFileObserver;
use App\Observers\EmployeeFaqObserver;
use App\Scopes\CompanyScope;

class EmployeeFaq extends BaseModel
{

    protected static function boot()
    {
        parent::boot();

        static::observe(EmployeeFaqObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function category()
    {
        return $this->belongsTo(EmployeeFaqCategory::class);
    }

    public function files()
    {
        return $this->hasMany(EmployeeFaqFile::class);
    }
}
