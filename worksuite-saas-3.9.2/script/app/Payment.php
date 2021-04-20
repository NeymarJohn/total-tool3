<?php

namespace App;

use App\Observers\PaymentObserver;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Payment extends BaseModel
{
    protected $dates = ['paid_on'];

    protected $appends = ['total_amount', 'paid_date', 'file_url'];

    protected static function boot()
    {
        parent::boot();

        static::observe(PaymentObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withoutGlobalScopes(['enable']);
    }

    public function offlineMethod()
    {
        return $this->belongsTo(OfflinePaymentMethod::class, 'offline_method_id');
    }

    public function getTotalAmountAttribute()
    {

        if (!is_null($this->amount) && !is_null($this->currency_symbol) && !is_null($this->currency_code)) {
            return  $this->amount;
        }

        return "";
    }

    public function getPaidDateAttribute()
    {
        if (!is_null($this->paid_on)) {
            return Carbon::parse($this->paid_on)->format('d F, Y H:i A');
        }
        return "";
    }

    public function getFileUrlAttribute()
    {
        return asset_url_local_s3('payment-receipt/'.$this->bill);
    }
}
