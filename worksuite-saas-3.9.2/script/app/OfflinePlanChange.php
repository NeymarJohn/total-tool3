<?php

namespace App;

use App\Observers\OfflinePlanChangeObserver;
use Illuminate\Database\Eloquent\Model;

class OfflinePlanChange extends BaseModel
{
    protected $appends = ['file'];


    protected static function boot()
    {
        parent::boot();
        static::observe(OfflinePlanChangeObserver::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function offline_method()
    {
        return $this->belongsTo(OfflinePaymentMethod::class, 'offline_method_id');
    }

    public function getFileAttribute()
    {
        return ($this->file_name) ? asset_url('offline-payment-files/' . $this->file_name) : asset('img/default-profile-3.png');
    }
}
